# Inertia View Model

## Installation

```json
{
  "require": {
    "returnless/inertia-view-model": "dev-main"
  },
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/Returnless-com/inertia-view-model.git"
    }
  ]
}
```

## Usage

```php
final class MyModelViewModel extends InertiaViewModel
{
    public function __construct(
        private readonly MyModel $myModel,
    ) {}
    
    public function id(): int
    {
        return $this->myModel->id;
    }
}

final class DummyViewModel extends InertiaViewModel
{
    protected string $viewPath = 'path-to-my-page';

    public function __construct(
        private readonly MyModel $myModel,
    ) {}

    public function myModel(): MyModelViewModel
    {
        return new MyModelViewModel($this->myModel);
    }
}

final class MyController
{
    public function __invoke(MyModel $myModel): InertiaViewModel
    {
        return new DummyViewModel($myModel);
    }
}
```
