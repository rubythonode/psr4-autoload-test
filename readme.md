# PSR-4 Autoload Test

이 프로젝트는 하나의 파일이 여러 개의 클래스를 담고 있을 때, PSR-4 오토로드 가능성을 실험하기 위한 프로젝트다. 

배경을 간략히 남겨 놓는다. 필자가 속한 팀에서는 멀티 플랫폼 클라이언트를 수용해야 하는 서버를 개발하고 있다. 해서 [Apache Thrift](https://thrift.apache.org/)라는 녀석을 이용하는데, 이 도구는 서버와 클라이언트 간의 인터페이스(코드)를 자동으로 만들어 준다. 자동 생성된 코드는 PSR-2(파일 하나당 클래스 하나, 파일명은 클래스명과 동일하게) 규칙을 따르지 않고 파일 하나에 수 십 개의 인터페이스와 클래스를 담고 있다.

모든 것은 Apache Thrift 팀의 PHP의 PSR 표준 이해 부족에서 발생한 것으로, 해당 팀에는 고쳐달라 요청할 것이다.

## 잠정 결론

> ~~**안된다(실험 #1 참고).**~~

> **된다(실험 #2 참고).** 
>
> [wan2land](http://blog.wani.kr/about/)님께서 [PR](https://github.com/appkr/psr4-autoload-test/pull/1)해 주셨습니다.

요약하자면, 

-   하나의 파일에 여러 개의 클래스를 선언할 때는 파일에 포함된 아무 클래스 이름이나 파일 이름으로 한다. 가령, `class Other`와 `class Another`를 포함한 PHP 스크립트라면 파일이름을 `Other.php` 또는 `Another.php`로 하면 된다.
-   다만, 클래스 호출 순서가 중요한데, `class Other` → `class Another` 순으로 PHP 스크립트를 작성했다면, 클래스를 호출하는 쪽에서 `class Another`보다 `class Other`가 먼저 호출되어야 한다.
    컴포저가 오토로드를 위한 레지스트리를 만들때 파일에 가장 먼저 나오는 `class` 키워드만 보고, 이하에 있는 `class` 키워드는 무시하기 때문으로 추정된다.

> **더 나은 방법(실험 #3 참고)**
>
> [a2](https://github.com/ani2life)님께서 [Modern PHP User Group 슬랙](http://slack-invite.modernpug.org/)을 통해서 조언해 주셨습니다.

요약하자면,

-   PSR-2 규칙을 어긴 파일에 포함된 클래스를 심볼링 링크로 만들어 준다.
-   예를 들어, `Collection.php`가 `Foo`와 `Bar` 클래스를 담고 있다면, `Collection.php`와 같은 경로에 `Foo.php`, `Bar.php` 심볼릭 링크를 만들어 준다.

## 실험 #1

저장소를 복제한 후 다음 명령을 실행한다.

```sh
# psr-2 규칙에 따라 파일 하나에 클래스 하나만 정의: 성공

~/psr4-autoload-test $ php conform.php
# App\Conform\Foo called
# App\Conform\Bar called
```

```sh
# psr-2 규칙을 어기고 파일 하나에 여러 개의 클래스 정의
# 담고 있는 클래스와 다른 파일명 사용: 오류

~/psr4-autoload-test $ php violate.php
# PHP Fatal error:  Uncaught Error: Class 'App\Violate\Foo' not found in ~/psr4-autoload-test/violate.php:5
```

## 실험 #2

```sh
# psr-2 규칙을 어기고 파일 하나에 여러 개의 클래스 정의
# 파일 내에서 최우선 선언한 클래스를 먼저 호출: 성공

~/psr4-autoload-test $ php run-other-first.php
# App\Violate\Other called
# App\Violate\Another called
```

```sh
# psr-2 규칙을 어기고 파일 하나에 여러 개의 클래스 정의
# 파일 내에서 최우선 선언하지 않은 클래스를 먼저 호출: 오류

~/psr4-autoload-test $ php run-another-first.php
# PHP Fatal error:  Uncaught Error: Class 'App\Violate\Another' not found in ~/psr4-autoload-test/run-another-first.php:7
```

## 실험 #3

```sh
# 필자의 환경과 경로가 다르므로 심볼릭 링크를 지우고 다시 만든다.

~/psr4-autoload-test $ rm src/Violate/Foo.php src/Violate/Bar.php
~/psr4-autoload-test $ ln -nfs src/Violate/Collection.php src/Violate/Foo.php
~/psr4-autoload-test $ ln -nfs src/Violate/Collection.php src/Violate/Bar.php
~/psr4-autoload-test $ composer dump-autoload
```

```sh
# psr-2 규칙을 어기고 파일 하나에 여러 개의 클래스 정의
# 클래스 이름에 해당하는 심볼릭 링크를 생성: 성공

~/psr4-autoload-test $ php violate.php
# App\Violate\Foo called
# App\Violate\Bar called
```

## 다른 조언 구합니다.

a2님 조언을 수용하면, PSR-2 규칙을 어긴 파일을 인자로 받아 파일을 읽어 `class Something`에 해당하는 라인을 찾아(또는 클래스 이름을 인자로 받아) 심볼릭 링크를 자동으로 생성하는 쉘 스크립트를 짤 수 있다. 이제 더 이상 `spl_autoload_register()` 함수로 커스텀 오토로드 로직을 만들지 않고도 PSR-4 오토로트를 이용할 수 있다.

다만 아직 실무에 적용하기 전이므로 안정성을 확인하지 못했다. 

> 선구자가 계시다면 안정성에 대한 경험담을 깃허브 이슈에 남겨 주시면 고맙겠습니다.