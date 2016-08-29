# PSR-4 Autoload Test

이 프로젝트는 하나의 파일이 여러 개의 클래스를 담고 있을 때, PSR-4 오토로드 가능성을 실험하기 위한 프로젝트다.

## 잠정 결론

안된다.

## 실험 방법

저장소를 복제한 후 다음 명령을 실행한다.

```sh
# psr-2 규칙에 따라 파일 하나에 클래스 하나만 정의

~/psr4-autoload $ php conform.php
# App\Conform\Foo called
# App\Conform\Bar called
```

```sh
# psr-2 규칙을 어기고 파일 하나에 여러 개의 클래스 정의

~/psr4-autoload $ php violate.php
# PHP Fatal error:  Uncaught Error: Class 'App\Violate\Foo' not found in ~/psr4-autoload/violate.php:5
```

## 조언 구합니다.

`spl_auto_register()`를 구현하지 않고, 하나의 파일에 여러 개의 클래스를 정의했을 때 오토로드 할 수 있는 방법을 찾고 있습니다.