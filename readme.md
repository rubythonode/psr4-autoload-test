# PSR-4 Autoload Test

이 프로젝트는 하나의 파일이 여러 개의 클래스를 담고 있을 때, PSR-4 오토로드 가능성을 실험하기 위한 프로젝트다.

## 잠정 결론

> ~~안된다.~~

> **된다.** 
>
> [wan2land](https://github.com/appkr/psr4-autoload-test/pull/1)님이 PR해 주셨습니다.

요약하자면, 

-   하나의 파일에 여러 개의 클래스를 선언할 때는 파일에 포함된 아무 클래스 이름이나 파일 이름으로 한다. 가령, `class Other`와 `class Another`를 포함한 PHP 스크립트라면 파일이름을 `Other.php` 또는 `Another.php`로 하면 된다.

-   다만, 클래스 호출 순서가 중요한데, `class Other` → `class Another` 순으로 PHP 스크립트를 작성했다면, 클래스를 호출하는 쪽에서 `class Another`보다 `class Other`가 먼저 호출되어야 한다.
    컴포저가 오토로드를 위한 레지스트리를 만들때 파일에 가장 먼저 나오는 `class` 키워드만 보고, 이하에 있는 `class` 키워드는 무시하기 때문으로 추정된다.

## 실험 방법

저장소를 복제한 후 다음 명령을 실행한다.

```sh
# psr-2 규칙에 따라 파일 하나에 클래스 하나만 정의

~/psr4-autoload-test $ php conform.php
# App\Conform\Foo called
# App\Conform\Bar called
```

```sh
# psr-2 규칙을 어기고 파일 하나에 여러 개의 클래스 정의

~/psr4-autoload-test $ php violate.php
# PHP Fatal error:  Uncaught Error: Class 'App\Violate\Foo' not found in ~/psr4-autoload-test/violate.php:5
```

```sh
# psr-2 규칙을 어기고 파일 하나에 여러 개의 클래스 정의

~/psr4-autoload-test $ php violate.php
# PHP Fatal error:  Uncaught Error: Class 'App\Violate\Foo' not found in ~/psr4-autoload-test/violate.php:5
```

```sh
# psr-2 규칙을 어기고 파일 하나에 여러 개의 클래스 정의

~/psr4-autoload-test $ php run-other-first.php
App\Violate\Other called
App\Violate\Another called
```

```sh
# psr-2 규칙을 어기고 파일 하나에 여러 개의 클래스 정의

~/psr4-autoload-test $ php run-another-first.php
# PHP Fatal error:  Uncaught Error: Class 'App\Violate\Another' not found in ~/psr4-autoload-test/run-another-first.php:7
```

## 다른 조언 구합니다.

`spl_auto_register()`를 구현하지 않고, 하나의 파일에 여러 개의 클래스를 정의했을 때 오토로드 할 수 있는 방법을 찾고 있습니다.