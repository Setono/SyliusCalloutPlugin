# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Common Development Commands

- **Code analysis**: `composer run analyse` (uses Psalm)
- **Code style check**: `composer run check-style` (uses ECS)
- **Code style fix**: `composer run fix-style` (uses ECS with --fix)
- **Tests**: `composer run phpunit` (PHPUnit tests)
- **Database migrations**: `php bin/console doctrine:migrations:diff` then `php bin/console doctrine:migrations:migrate`
- **Assign callouts**: `php bin/console setono:sylius-callout:assign`
- **Install assets**: `bin/console assets:install`

## Project Architecture

This is a Sylius plugin that adds callout/badge functionality to products. Key architectural components:

### Core Components
- **Callout Model**: Main entity with translations (`src/Model/Callout*.php`)
- **CalloutRule Model**: Rule-based system for determining which products get callouts
- **Product Integration**: Extends Sylius Product entities via traits

### Rule System
- **Rule Checkers**: Located in `src/Checker/Rule/` - implement `CalloutRuleCheckerInterface`
- **Eligibility Checkers**: Located in `src/Checker/Eligibility/` - determine if callouts should be applied
- **Rendering Eligibility**: Located in `src/Checker/RenderingEligibility/` - determine if callouts should be displayed

### Message System
- **Commands**: Located in `src/Message/Command/` - all extend `CommandInterface`
- **Handlers**: Located in `src/Message/Handler/` - process commands asynchronously
- **Batch Processing**: Uses `src/BatchIterator/` for efficient bulk operations

### Frontend Integration
- **Templates**: Located in `templates/` with admin and shop sections
- **Twig Extensions**: `src/Twig/` provides template functions like `get_callouts()`
- **CSS/JS**: Admin assets in `src/Resources/public/admin/`

### Translations
- Translations are inside `translations`.
- The primary language is `English`
- The translation files are YAML and the keys inside the files should be ordered alphabetically
- When translating, translate into these languages:
  - Danish
  - German
  - French
  - Dutch
  - Norwegian
  - Polish
  - Swedish
  - Italian
  - Spanish
  - Romanian
  - Lithuanian

### Configuration
- **Services**: Main config in `config/services.xml` with specialized service files in `config/services/`
- **Doctrine Mapping**: ORM mappings in `config/doctrine/model/`
- **Grids**: Admin grid configuration in `config/grids/`

## Development Notes

- **Plugin Structure**: This is a standard Sylius plugin following Symfony bundle conventions
- **Resource Management**: Uses Sylius Resource Bundle for entity management and Sylius Grid Bundle for admin grids
- **Rule System**: Extensible rule system using tagged services - new rules need both a checker class and form type
- **Async Processing**: Commands implement `CommandInterface` for async processing via Symfony Messenger
- **Entity Extension**: Uses traits to extend Sylius entities rather than inheritance
- **Test Application**: Full test application in `tests/Application/` for integration testing

## Key Extension Points

- **Adding New Rules**: Create rule checker in `src/Checker/Rule/` and form type in `src/Form/Type/Rule/`
- **Custom Rendering**: Override templates in `templates/` directory
- **Custom CSS Classes**: Implement `CssClassBuilderInterface` for different UI frameworks
