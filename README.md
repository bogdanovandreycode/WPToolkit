# WPToolKit

`WPToolKit` is a small toolkit for building WordPress plugins with OOP, attributes, and a lightweight DI container.

The library helps you register:

- REST routes
- actions
- filters
- admin pages
- meta boxes
- shortcodes
- widgets
- roles
- views

It is designed to avoid global helper state and internal static service locators, which makes runtime behavior more predictable when multiple plugins are active.

## Requirements

- PHP `^8.1`
- WordPress
- Composer

## Installation

```bash
composer require arraydev/wptoolkit
```

## Philosophy

- No global `app()` helper
- No internal static container state
- Controllers can be instantiated through a local `ServiceFactory`
- Attribute-based bootstrapping for template-method style base controllers

## Quick Start

Minimal plugin bootstrap:

```php
<?php
/**
 * Plugin Name: Demo Toolkit Plugin
 */

declare(strict_types=1);

use WpToolKit\Loader\AttributeLoader;

require_once __DIR__ . '/vendor/autoload.php';

add_action('plugins_loaded', function (): void {
    $loader = new AttributeLoader(
        'Vendor\\DemoPlugin',
        __DIR__ . '/src'
    );

    $loader->loadControllers();
});
```

Your plugin classes can then be initialized through PHP attributes.

## Attribute-Based Controllers

`AttributeLoader` scans a directory, finds classes with supported attributes, validates their parent controller, and creates them through `ServiceFactory`.

Supported attributes:

- `#[Route(...)]`
- `#[Action(...)]`
- `#[Filter(...)]`
- `#[Page(...)]`
- `#[MetaBox(...)]`
- `#[Shortcode(...)]`
- `#[Widget(...)]`

Important rules:

- One controller class can have only one controller attribute.
- A class with `#[Route]` must extend `RouteController`, `#[Action]` must extend `ActionController`, and so on.
- Attribute-loaded classes must be constructible from attribute arguments and container-resolved dependencies.

## REST Routes

Base controller: `WpToolKit\Controller\RouteController`

Attribute: `WpToolKit\Attribute\Route`

Example:

```php
<?php

declare(strict_types=1);

namespace Vendor\DemoPlugin\Http;

use WP_REST_Request;
use WpToolKit\Attribute\Route;
use WpToolKit\Controller\RouteController;

#[Route('demo-plugin/v1', '/ping', methods: 'GET')]
final class PingRoute extends RouteController
{
    public function callback(WP_REST_Request $request): mixed
    {
        return ['message' => 'pong'];
    }

    public function checkPermission(WP_REST_Request $request): bool
    {
        return current_user_can('read');
    }
}
```

### Route Params

If you need route params, pass param classes in the attribute.

Example:

```php
#[Route(
    'demo-plugin/v1',
    '/sync',
    params: [
        \Vendor\DemoPlugin\Http\Params\PostIdParam::class,
    ],
    methods: 'POST'
)]
```

Param classes must implement `WpToolKit\Interface\ParamRoureInterface`, usually by extending `WpToolKit\Controller\ParamRoute`.

Example:

```php
<?php

declare(strict_types=1);

namespace Vendor\DemoPlugin\Http\Params;

use WP_Error;
use WpToolKit\Controller\ParamRoute;

final class PostIdParam extends ParamRoute
{
    public function __construct()
    {
        parent::__construct('post_id', required: true);
    }

    public function validate($param, $request, $key): bool|WP_Error
    {
        return is_numeric($param);
    }

    public function sanitize($param, $request, $key): mixed
    {
        return (int) $param;
    }
}
```

## Actions

Base controller: `WpToolKit\Controller\ActionController`

Attribute: `WpToolKit\Attribute\Action`

Example:

```php
<?php

declare(strict_types=1);

namespace Vendor\DemoPlugin\Hooks;

use WpToolKit\Attribute\Action;
use WpToolKit\Controller\ActionController;

#[Action('init', priority: 20)]
final class RegisterSomethingAction extends ActionController
{
    public function handle(...$args): void
    {
        // Your logic here
    }
}
```

## Filters

Base controller: `WpToolKit\Controller\FilterController`

Attribute: `WpToolKit\Attribute\Filter`

Example:

```php
<?php

declare(strict_types=1);

namespace Vendor\DemoPlugin\Hooks;

use WpToolKit\Attribute\Filter;
use WpToolKit\Controller\FilterController;

#[Filter('the_title', priority: 10, acceptedArgs: 2)]
final class TitleFilter extends FilterController
{
    public function handle(...$args): mixed
    {
        $title = $args[0] ?? '';

        return '[Demo] ' . $title;
    }
}
```

## Admin Pages

Base controller: `WpToolKit\Controller\AdminPage`

Attribute: `WpToolKit\Attribute\Page`

Example:

```php
<?php

declare(strict_types=1);

namespace Vendor\DemoPlugin\Admin;

use WpToolKit\Attribute\Page;
use WpToolKit\Controller\AdminPage;

#[Page(
    'Demo Settings',
    'Demo Settings',
    'manage_options',
    'demo-settings',
    25,
    icon: 'dashicons-admin-generic'
)]
final class SettingsPage extends AdminPage
{
    public function render(): void
    {
        echo '<div class="wrap"><h1>Demo Settings</h1></div>';
    }

    public function callback(): void
    {
        // Handle POST request for this page
    }
}
```

Submenu example:

```php
#[Page(
    'Logs',
    'Logs',
    'manage_options',
    'demo-logs',
    30,
    isSubManuItem: true,
    parentUrl: 'demo-settings'
)]
```

## Meta Boxes

Base controller: `WpToolKit\Controller\MetaBoxController`

Attribute: `WpToolKit\Attribute\MetaBox`

Example:

```php
<?php

declare(strict_types=1);

namespace Vendor\DemoPlugin\Admin;

use WpToolKit\Attribute\MetaBox;
use WpToolKit\Controller\MetaBoxController;
use WpToolKit\Entity\MetaBoxContext;
use WpToolKit\Entity\MetaBoxPriority;

#[MetaBox(
    'demo_box',
    'Demo Box',
    'post',
    MetaBoxContext::SIDE,
    MetaBoxPriority::HIGH
)]
final class DemoMetaBox extends MetaBoxController
{
    public function render($post): void
    {
        echo '<p>Meta box content</p>';
    }

    public function callback($postId): void
    {
        // Save logic
    }
}
```

## Shortcodes

Base controller: `WpToolKit\Controller\ShortcodeController`

Attribute: `WpToolKit\Attribute\Shortcode`

Example:

```php
<?php

declare(strict_types=1);

namespace Vendor\DemoPlugin\Shortcodes;

use WpToolKit\Attribute\Shortcode;
use WpToolKit\Controller\ShortcodeController;

#[Shortcode('demo_badge', ['label' => 'Default Badge'])]
final class BadgeShortcode extends ShortcodeController
{
    public function render($atts, $content): string
    {
        $atts = $this->getAtts($atts);

        return '<span class="demo-badge">' . esc_html($atts['label']) . '</span>';
    }
}
```

## Widgets

Base controller: `WpToolKit\Controller\WidgetsController`

Attribute: `WpToolKit\Attribute\Widget`

Example:

```php
<?php

declare(strict_types=1);

namespace Vendor\DemoPlugin\Widgets;

use WpToolKit\Attribute\Widget;
use WpToolKit\Controller\WidgetsController;

#[Widget('demo_widget', 'Demo Widget', 'Simple demo widget')]
final class DemoWidget extends WidgetsController
{
    public function widget($args, $instance): void
    {
        echo $args['before_widget'] ?? '';
        echo '<p>Demo widget output</p>';
        echo $args['after_widget'] ?? '';
    }

    public function form($instance): void
    {
        echo '<p>No settings</p>';
    }

    public function update($new_instance, $old_instance): array
    {
        return $old_instance;
    }
}
```

## ServiceFactory

`WpToolKit\Factory\ServiceFactory` is a lightweight container for local plugin runtime.

Supported methods:

- `bind()`
- `singleton()`
- `instance()`
- `make()`
- `get()`
- `has()`
- `call()`

Example:

```php
<?php

declare(strict_types=1);

use Vendor\DemoPlugin\Service\Mailer;
use WpToolKit\Factory\ServiceFactory;

$container = new ServiceFactory();

$container->singleton(Mailer::class);

$mailer = $container->make(Mailer::class);
```

Passing runtime arguments:

```php
$postController = $container->make(
    \WpToolKit\Controller\PostController::class,
    ['post' => $postEntity]
);
```

Use a shared container with `AttributeLoader`:

```php
$container = new ServiceFactory();

$loader = new AttributeLoader(
    'Vendor\\DemoPlugin',
    __DIR__ . '/src',
    $container
);

$loader->loadControllers();
```

## Classic Controllers Without Attributes

You can still use controllers manually if that fits your plugin better.

Example:

```php
<?php

declare(strict_types=1);

use WpToolKit\Controller\ScriptController;
use WpToolKit\Entity\ScriptType;

$scripts = new ScriptController();
$scripts->addStyle('demo-style', '/assets/style.css', ScriptType::ADMIN);
```

## Roles

Manager: `WpToolKit\Manager\RoleManager`

Example:

```php
<?php

declare(strict_types=1);

use WpToolKit\Manager\RoleManager;

$roles = new RoleManager();

$roles->addRole('demo_manager', 'Demo Manager', [
    'read' => true,
    'edit_posts' => true,
]);
```

Loading roles from YAML:

```php
$roles->loadFromYaml(__DIR__ . '/config/roles.yaml');
```

Example YAML:

```yaml
demo_manager:
  display_name: Demo Manager
  capabilities:
    read: true
    edit_posts: true
```

## Views

Controller: `WpToolKit\Controller\ViewLoader`

Example:

```php
<?php

declare(strict_types=1);

use WpToolKit\Controller\ViewLoader;
use WpToolKit\Entity\View;

$views = new ViewLoader();

$views->add(new View(
    'settings',
    __DIR__ . '/views/settings.php',
    ['title' => 'Demo Settings']
));

$views->load('settings');
```

Loading views from YAML:

```php
$views->loadFromYaml(
    __DIR__ . '/config/views.yaml',
    plugin_dir_path(__FILE__)
);
```

Example YAML:

```yaml
settings: /views/settings.php
logs: /views/logs.php
```

## Menu and Script Controllers

### MenuController

```php
use WpToolKit\Controller\MenuController;

$menu = new MenuController();
$menu->addItem(
    'Demo',
    'Demo',
    'manage_options',
    'demo-page',
    fn () => print 'Demo page',
    'dashicons-admin-generic',
    25
);
```

### ScriptController

```php
use WpToolKit\Controller\ScriptController;
use WpToolKit\Entity\ScriptType;

$scripts = new ScriptController();

$scripts->addStyle('demo-style', '/assets/style.css', ScriptType::ADMIN);
$scripts->addScript('demo-script', '/assets/app.js', ScriptType::FRONT);
```

## Recommended Project Structure

```text
my-plugin/
├─ my-plugin.php
├─ composer.json
├─ vendor/
├─ src/
│  ├─ Admin/
│  ├─ Controllers/
│  ├─ Hooks/
│  ├─ Http/
│  │  ├─ Params/
│  │  ├─ Routes/
│  │  └─ Requests/
│  ├─ Shortcodes/
│  ├─ Widgets/
│  └─ Service/
├─ views/
└─ config/
```

## Notes About Multiple Plugins and Vendors

`WPToolKit` no longer relies on internal static service state, which reduces conflicts between plugins.

However, WordPress still has a common Composer problem:

- if two plugins load different copies of the same PHP library
- and both copies use the same namespace
- the first loaded copy may win

If your plugin ships its own vendor directory, the safest production approach is to isolate namespaces with a tool such as `PHP-Scoper` or `Mozart`.

## Best Practices

- Create one `ServiceFactory` per plugin runtime.
- Pass the same container into `AttributeLoader`.
- Keep attribute-loaded controllers thin and focused.
- Put business logic in your own services and inject them through the container.
- Prefer namespaced classes inside your plugin.

## Limitations

- `AttributeLoader` expects classes to live under the namespace and directory you pass in.
- Controller attributes can only describe one base controller per class.
- Route params in attributes should be passed as class names, not arbitrary runtime objects.

## License

MIT
