<?php

namespace TailwindMerge\Support;

use TailwindMerge\Validators\AnyValueValidator;
use TailwindMerge\Validators\ArbitraryImageValidator;
use TailwindMerge\Validators\ArbitraryLengthValidator;
use TailwindMerge\Validators\ArbitraryNumberValidator;
use TailwindMerge\Validators\ArbitraryPositionValidator;
use TailwindMerge\Validators\ArbitraryShadowValidator;
use TailwindMerge\Validators\ArbitrarySizeValidator;
use TailwindMerge\Validators\ArbitraryValueValidator;
use TailwindMerge\Validators\IntegerValidator;
use TailwindMerge\Validators\LengthValidator;
use TailwindMerge\Validators\NumberValidator;
use TailwindMerge\Validators\PercentValidator;
use TailwindMerge\Validators\TshirtSizeValidator;
use TailwindMerge\ValueObjects\ThemeGetter;

class Config
{
    /**
     * @var array<string, mixed>
     */
    private static array $additionalConfig = [];

    /**
     * @return array<string, mixed>
     */
    public static function getMergedConfig(): array
    {
        $config = self::getDefaultConfig();

        foreach (self::$additionalConfig as $key => $additionalConfig) {
            $config[$key] = self::mergePropertyRecursively($config, $key, $additionalConfig);
        }

        return $config;
    }

    private static function mergePropertyRecursively(array $baseConfig, string $mergeKey, array|bool|float|int|string|null $mergeValue): array|bool|float|int|string|null
    {
        if (! array_key_exists($mergeKey, $baseConfig)) {
            return $mergeValue;
        }
        if (is_string($mergeValue)) {
            return $mergeValue;
        }
        if (is_numeric($mergeValue)) {
            return $mergeValue;
        }
        if (is_bool($mergeValue)) {
            return $mergeValue;
        }
        if ($mergeValue === null) {
            return $mergeValue;
        }
        if (is_array($mergeValue) && array_is_list($mergeValue) && is_array($baseConfig[$mergeKey]) && array_is_list($baseConfig[$mergeKey])) {
            return [...$baseConfig[$mergeKey], ...$mergeValue];
        }

        if (is_array($mergeValue) && ! array_is_list($mergeValue) /* && is_array($baseConfig[$mergeKey]) && array_is_list($baseConfig[$mergeKey]) */) {
            if ($baseConfig[$mergeKey] === null) {
                return $mergeValue;
            }

            foreach ($mergeValue as $key => $value) {
                $baseConfig[$mergeKey][$key] = self::mergePropertyRecursively($baseConfig[$mergeKey], $key, $value);
            }
        }

        return $baseConfig[$mergeKey];
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function setAdditionalConfig(array $config): void
    {
        self::$additionalConfig = $config;
    }

    /**
     * @return array{cacheSize: int, prefix: ?string, theme: array<string, mixed>, classGroups: array<string, mixed>,conflictingClassGroups: array<string, array<int, string>>, conflictingClassGroupModifiers: array<string, array<int, string>>}
     */
    public static function getDefaultConfig(): array
    {
        $colors = self::fromTheme('colors');
        $spacing = self::fromTheme('spacing');
        $blur = self::fromTheme('blur');
        $brightness = self::fromTheme('brightness');
        $borderColor = self::fromTheme('borderColor');
        $borderRadius = self::fromTheme('borderRadius');
        $borderSpacing = self::fromTheme('borderSpacing');
        $borderWidth = self::fromTheme('borderWidth');
        $contrast = self::fromTheme('contrast');
        $grayscale = self::fromTheme('grayscale');
        $hueRotate = self::fromTheme('hueRotate');
        $invert = self::fromTheme('invert');
        $gap = self::fromTheme('gap');
        $gradientColorStops = self::fromTheme('gradientColorStops');
        $gradientColorStopPositions = self::fromTheme('gradientColorStopPositions');
        $inset = self::fromTheme('inset');
        $margin = self::fromTheme('margin');
        $opacity = self::fromTheme('opacity');
        $padding = self::fromTheme('padding');
        $saturate = self::fromTheme('saturate');
        $scale = self::fromTheme('scale');
        $sepia = self::fromTheme('sepia');
        $skew = self::fromTheme('skew');
        $space = self::fromTheme('space');
        $translate = self::fromTheme('translate');

        return [
            'cacheSize' => 500,
            'prefix' => null,
            'theme' => [
                'colors' => [AnyValueValidator::validate(...)],
                'spacing' => [LengthValidator::validate(...), ArbitraryLengthValidator::validate(...)],
                'blur' => ['none', '', TshirtSizeValidator::validate(...), ArbitraryValueValidator::validate(...)],
                'brightness' => self::getNumber(),
                'borderColor' => [$colors],
                'borderRadius' => ['none', '', 'full', TshirtSizeValidator::validate(...), ArbitraryValueValidator::validate(...)],
                'borderSpacing' => self::getSpacingWithArbitrary($spacing),
                'borderWidth' => self::getLengthWithEmptyAndArbitrary(),
                'contrast' => self::getNumber(),
                'grayscale' => self::getZeroAndEmpty(),
                'hueRotate' => self::getNumberAndArbitrary(),
                'invert' => self::getZeroAndEmpty(),
                'gap' => self::getSpacingWithArbitrary($spacing),
                'gradientColorStops' => [$colors],
                'gradientColorStopPositions' => [PercentValidator::validate(...), ArbitraryLengthValidator::validate(...)],
                'inset' => self::getSpacingWithAutoAndArbitrary($spacing),
                'margin' => self::getSpacingWithAutoAndArbitrary($spacing),
                'opacity' => self::getNumber(),
                'padding' => self::getSpacingWithArbitrary($spacing),
                'saturate' => self::getNumber(),
                'scale' => self::getNumber(),
                'sepia' => self::getZeroAndEmpty(),
                'skew' => self::getNumberAndArbitrary(),
                'space' => self::getSpacingWithArbitrary($spacing),
                'translate' => self::getSpacingWithArbitrary($spacing),
            ],
            'classGroups' => [
                // Layout
                /**
                 * Aspect Ratio
                 *
                 * @see https://tailwindcss.com/docs/aspect-ratio
                 */
                'aspect' => [['aspect' => ['auto', 'square', 'video', ArbitraryValueValidator::validate(...)]]],
                /**
                 * Container
                 *
                 * @see https://tailwindcss.com/docs/container
                 */
                'container' => ['container'],
                /**
                 * Columns
                 *
                 * @see https://tailwindcss.com/docs/columns
                 */
                'columns' => [['columns' => [TshirtSizeValidator::validate(...)]]],
                /**
                 * Break After
                 *
                 * @see https://tailwindcss.com/docs/break-after
                 */
                'break-after' => [['break-after' => self::getBreaks()]],
                /**
                 * Break Before
                 *
                 * @see https://tailwindcss.com/docs/break-before
                 */
                'break-before' => [['break-before' => self::getBreaks()]],
                /**
                 * Break Inside
                 *
                 * @see https://tailwindcss.com/docs/break-inside
                 */
                'break-inside' => [['break-inside' => ['auto', 'avoid', 'avoid-page', 'avoid-column']]],
                /**
                 * Box Decoration Break
                 *
                 * @see https://tailwindcss.com/docs/box-decoration-break
                 */
                'box-decoration' => [['box-decoration' => ['slice', 'clone']]],
                /**
                 * Box Sizing
                 *
                 * @see https://tailwindcss.com/docs/box-sizing
                 */
                'box' => [['box' => ['border', 'content']]],
                /**
                 * Display
                 *
                 * @see https://tailwindcss.com/docs/display
                 */
                'display' => [
                    'block',
                    'inline-block',
                    'inline',
                    'flex',
                    'inline-flex',
                    'table',
                    'inline-table',
                    'table-caption',
                    'table-cell',
                    'table-column',
                    'table-column-group',
                    'table-footer-group',
                    'table-header-group',
                    'table-row-group',
                    'table-row',
                    'flow-root',
                    'grid',
                    'inline-grid',
                    'contents',
                    'list-item',
                    'hidden',
                ],
                /**
                 * Floats
                 *
                 * @see https://tailwindcss.com/docs/float
                 */
                'float' => [['float' => ['right', 'left', 'none', 'start', 'end']]],
                /**
                 * Clear
                 *
                 * @see https://tailwindcss.com/docs/clear
                 */
                'clear' => [['clear' => ['left', 'right', 'both', 'none', 'start', 'end']]],
                /**
                 * Isolation
                 *
                 * @see https://tailwindcss.com/docs/isolation
                 */
                'isolation' => ['isolate', 'isolation-auto'],
                /**
                 * Object Fit
                 *
                 * @see https://tailwindcss.com/docs/object-fit
                 */
                'object-fit' => [['object' => ['contain', 'cover', 'fill', 'none', 'scale-down']]],
                /**
                 * Object Position
                 *
                 * @see https://tailwindcss.com/docs/object-position
                 */
                'object-position' => [['object' => [...self::getPositions(), ArbitraryValueValidator::validate(...)]]],
                /**
                 * Overflow
                 *
                 * @see https://tailwindcss.com/docs/overflow
                 */
                'overflow' => [['overflow' => self::getOverflow()]],
                /**
                 * Overflow X
                 *
                 * @see https://tailwindcss.com/docs/overflow
                 */
                'overflow-x' => [['overflow-x' => self::getOverflow()]],
                /**
                 * Overflow Y
                 *
                 * @see https://tailwindcss.com/docs/overflow
                 */
                'overflow-y' => [['overflow-y' => self::getOverflow()]],
                /**
                 * Overscroll Behavior
                 *
                 * @see https://tailwindcss.com/docs/overscroll-behavior
                 */
                'overscroll' => [['overscroll' => self::getOverscroll()]],
                /**
                 * Overscroll Behavior X
                 *
                 * @see https://tailwindcss.com/docs/overscroll-behavior
                 */
                'overscroll-x' => [['overscroll-x' => self::getOverscroll()]],
                /**
                 * Overscroll Behavior Y
                 *
                 * @see https://tailwindcss.com/docs/overscroll-behavior
                 */
                'overscroll-y' => [['overscroll-y' => self::getOverscroll()]],
                /**
                 * Position
                 *
                 * @see https://tailwindcss.com/docs/position
                 */
                'position' => ['static', 'fixed', 'absolute', 'relative', 'sticky'],
                /**
                 * Top / Right / Bottom / Left
                 *
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'inset' => [['inset' => [$inset]]],
                /**
                 * Right / Left
                 *
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'inset-x' => [['inset-x' => [$inset]]],
                /**
                 * Top / Bottom
                 *
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'inset-y' => [['inset-y' => [$inset]]],
                /**
                 * Start
                 *
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'start' => [['start' => [$inset]]],
                /**
                 * End
                 *
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'end' => [['end' => [$inset]]],
                /**
                 * Top
                 *
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'top' => [['top' => [$inset]]],
                /**
                 * Right
                 *
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'right' => [['right' => [$inset]]],
                /**
                 * Bottom
                 *
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'bottom' => [['bottom' => [$inset]]],
                /**
                 * Left
                 *
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'left' => [['left' => [$inset]]],
                /**
                 * Visibility
                 *
                 * @see https://tailwindcss.com/docs/visibility
                 */
                'visibility' => ['visible', 'invisible', 'collapse'],
                /**
                 * Z-Index
                 *
                 * @see https://tailwindcss.com/docs/z-index
                 */
                'z' => [['z' => ['auto', IntegerValidator::validate(...), ArbitraryValueValidator::validate(...)]]],
                // Flexbox and Grid
                /**
                 * Flex Basis
                 *
                 * @see https://tailwindcss.com/docs/flex-basis
                 */
                'basis' => [['basis' => self::getSpacingWithAutoAndArbitrary($space)]],
                /**
                 * Flex Direction
                 *
                 * @see https://tailwindcss.com/docs/flex-direction
                 */
                'flex-direction' => [['flex' => ['row', 'row-reverse', 'col', 'col-reverse']]],
                /**
                 * Flex Wrap
                 *
                 * @see https://tailwindcss.com/docs/flex-wrap
                 */
                'flex-wrap' => [['flex' => ['wrap', 'wrap-reverse', 'nowrap']]],
                /**
                 * Flex
                 *
                 * @see https://tailwindcss.com/docs/flex
                 */
                'flex' => [['flex' => ['1', 'auto', 'initial', 'none', ArbitraryValueValidator::validate(...)]]],
                /**
                 * Flex Grow
                 *
                 * @see https://tailwindcss.com/docs/flex-grow
                 */
                'grow' => [['grow' => self::getZeroAndEmpty()]],
                /**
                 * Flex Shrink
                 *
                 * @see https://tailwindcss.com/docs/flex-shrink
                 */
                'shrink' => [['shrink' => self::getZeroAndEmpty()]],
                /**
                 * Order
                 *
                 * @see https://tailwindcss.com/docs/order
                 */
                'order' => [['order' => ['first', 'last', 'none', IntegerValidator::validate(...), ArbitraryValueValidator::validate(...)]]],
                /**
                 * Grid Template Columns
                 *
                 * @see https://tailwindcss.com/docs/grid-template-columns
                 */
                'grid-cols' => [['grid-cols' => [AnyValueValidator::validate(...)]]],
                /**
                 * Grid Column Start / End
                 *
                 * @see https://tailwindcss.com/docs/grid-column
                 */
                'col-start-end' => [['col' => ['auto', ['span' => ['full', IntegerValidator::validate(...), ArbitraryValueValidator::validate(...)]], ArbitraryValueValidator::validate(...)]]],
                /**
                 * Grid Column Start
                 *
                 * @see https://tailwindcss.com/docs/grid-column
                 */
                'col-start' => [['col-start' => self::getNumberWithAutoAndArbitrary()]],
                /**
                 * Grid Column End
                 *
                 * @see https://tailwindcss.com/docs/grid-column
                 */
                'col-end' => [['col-end' => self::getNumberWithAutoAndArbitrary()]],
                /**
                 * Grid Template Rows
                 *
                 * @see https://tailwindcss.com/docs/grid-template-rows
                 */
                'grid-rows' => [['grid-rows' => [AnyValueValidator::validate(...)]]],
                /**
                 * Grid Row Start / End
                 *
                 * @see https://tailwindcss.com/docs/grid-row
                 */
                'row-start-end' => [['row' => ['auto', ['span' => [IntegerValidator::validate(...), ArbitraryValueValidator::validate(...)]], ArbitraryValueValidator::validate(...)]]],
                /**
                 * Grid Row Start
                 *
                 * @see https://tailwindcss.com/docs/grid-row
                 */
                'row-start' => [['row-start' => self::getNumberWithAutoAndArbitrary()]],
                /**
                 * Grid Row End
                 *
                 * @see https://tailwindcss.com/docs/grid-row
                 */
                'row-end' => [['row-end' => self::getNumberWithAutoAndArbitrary()]],
                /**
                 * Grid Auto Flow
                 *
                 * @see https://tailwindcss.com/docs/grid-auto-flow
                 */
                'grid-flow' => [['grid-flow' => ['row', 'col', 'dense', 'row-dense', 'col-dense']]],
                /**
                 * Grid Auto Columns
                 *
                 * @see https://tailwindcss.com/docs/grid-auto-columns
                 */
                'auto-cols' => [['auto-cols' => ['auto', 'min', 'max', 'fr', ArbitraryValueValidator::validate(...)]]],
                /**
                 * Grid Auto Rows
                 *
                 * @see https://tailwindcss.com/docs/grid-auto-rows
                 */
                'auto-rows' => [['auto-rows' => ['auto', 'min', 'max', 'fr', ArbitraryValueValidator::validate(...)]]],
                /**
                 * Gap
                 *
                 * @see https://tailwindcss.com/docs/gap
                 */
                'gap' => [['gap' => [$gap]]],
                /**
                 * Gap X
                 *
                 * @see https://tailwindcss.com/docs/gap
                 */
                'gap-x' => [['gap-x' => [$gap]]],
                /**
                 * Gap Y
                 *
                 * @see https://tailwindcss.com/docs/gap
                 */
                'gap-y' => [['gap-y' => [$gap]]],
                /**
                 * Justify Content
                 *
                 * @see https://tailwindcss.com/docs/justify-content
                 */
                'justify-content' => [['justify' => ['normal', ...self::getAlign()]]],
                /**
                 * Justify Items
                 *
                 * @see https://tailwindcss.com/docs/justify-items
                 */
                'justify-items' => [['justify-items' => ['start', 'end', 'center', 'stretch']]],
                /**
                 * Justify Self
                 *
                 * @see https://tailwindcss.com/docs/justify-self
                 */
                'justify-self' => [['justify-self' => ['auto', 'start', 'end', 'center', 'stretch']]],
                /**
                 * Align Content
                 *
                 * @see https://tailwindcss.com/docs/align-content
                 */
                'align-content' => [['content' => ['normal', ...self::getAlign(), 'baseline']]],
                /**
                 * Align Items
                 *
                 * @see https://tailwindcss.com/docs/align-items
                 */
                'align-items' => [['items' => ['start', 'end', 'center', 'baseline', 'stretch']]],
                /**
                 * Align Self
                 *
                 * @see https://tailwindcss.com/docs/align-self
                 */
                'align-self' => [['self' => ['auto', 'start', 'end', 'center', 'stretch', 'baseline']]],
                /**
                 * Place Content
                 *
                 * @see https://tailwindcss.com/docs/place-content
                 */
                'place-content' => [['place-content' => [...self::getAlign(), 'baseline']]],
                /**
                 * Place Items
                 *
                 * @see https://tailwindcss.com/docs/place-items
                 */
                'place-items' => [['place-items' => ['start', 'end', 'center', 'baseline', 'stretch']]],
                /**
                 * Place Self
                 *
                 * @see https://tailwindcss.com/docs/place-self
                 */
                'place-self' => [['place-self' => ['auto', 'start', 'end', 'center', 'stretch']]],
                // Spacing
                /**
                 * Padding
                 *
                 * @see https://tailwindcss.com/docs/padding
                 */
                'p' => [['p' => [$padding]]],
                /**
                 * Padding X
                 *
                 * @see https://tailwindcss.com/docs/padding
                 */
                'px' => [['px' => [$padding]]],
                /**
                 * Padding Y
                 *
                 * @see https://tailwindcss.com/docs/padding
                 */
                'py' => [['py' => [$padding]]],
                /**
                 * Padding Start
                 *
                 * @see https://tailwindcss.com/docs/padding
                 */
                'ps' => [['ps' => [$padding]]],
                /**
                 * Padding End
                 *
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pe' => [['pe' => [$padding]]],
                /**
                 * Padding Top
                 *
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pt' => [['pt' => [$padding]]],
                /**
                 * Padding Right
                 *
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pr' => [['pr' => [$padding]]],
                /**
                 * Padding Bottom
                 *
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pb' => [['pb' => [$padding]]],
                /**
                 * Padding Left
                 *
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pl' => [['pl' => [$padding]]],
                /**
                 * Margin
                 *
                 * @see https://tailwindcss.com/docs/margin
                 */
                'm' => [['m' => [$margin]]],
                /**
                 * Margin X
                 *
                 * @see https://tailwindcss.com/docs/margin
                 */
                'mx' => [['mx' => [$margin]]],
                /**
                 * Margin Y
                 *
                 * @see https://tailwindcss.com/docs/margin
                 */
                'my' => [['my' => [$margin]]],
                /**
                 * Margin Start
                 *
                 * @see https://tailwindcss.com/docs/margin
                 */
                'ms' => [['ms' => [$margin]]],
                /**
                 * Margin End
                 *
                 * @see https://tailwindcss.com/docs/margin
                 */
                'me' => [['me' => [$margin]]],
                /**
                 * Margin Top
                 *
                 * @see https://tailwindcss.com/docs/margin
                 */
                'mt' => [['mt' => [$margin]]],
                /**
                 * Margin Right
                 *
                 * @see https://tailwindcss.com/docs/margin
                 */
                'mr' => [['mr' => [$margin]]],
                /**
                 * Margin Bottom
                 *
                 * @see https://tailwindcss.com/docs/margin
                 */
                'mb' => [['mb' => [$margin]]],
                /**
                 * Margin Left
                 *
                 * @see https://tailwindcss.com/docs/margin
                 */
                'ml' => [['ml' => [$margin]]],
                /**
                 * Space Between X
                 *
                 * @see https://tailwindcss.com/docs/space
                 */
                'space-x' => [['space-x' => [$space]]],
                /**
                 * Space Between X Reverse
                 *
                 * @see https://tailwindcss.com/docs/space
                 */
                'space-x-reverse' => ['space-x-reverse'],
                /**
                 * Space Between Y
                 *
                 * @see https://tailwindcss.com/docs/space
                 */
                'space-y' => [['space-y' => [$space]]],
                /**
                 * Space Between Y Reverse
                 *
                 * @see https://tailwindcss.com/docs/space
                 */
                'space-y-reverse' => ['space-y-reverse'],
                // Sizing
                /**
                 * Width
                 *
                 * @see https://tailwindcss.com/docs/width
                 */
                'w' => [
                    [
                        'w' => [
                            'auto',
                            'min',
                            'max',
                            'fit',
                            'svw',
                            'lvw',
                            'dvw',
                            ArbitraryValueValidator::validate(...),
                            $spacing,
                        ],
                    ],
                ],
                /**
                 * Min-Width
                 *
                 * @see https://tailwindcss.com/docs/min-width
                 */
                'min-w' => [['min-w' => ['min', 'max', 'fit', ArbitraryValueValidator::validate(...), LengthValidator::validate(...)]]],
                /**
                 * Max-Width
                 *
                 * @see https://tailwindcss.com/docs/max-width
                 */
                'max-w' => [
                    [
                        'max-w' => [
                            ArbitraryValueValidator::validate(...),
                            $spacing,
                            'none',
                            'full',
                            'min',
                            'max',
                            'fit',
                            'prose',
                            ['screen' => [TshirtSizeValidator::validate(...)]],
                            TshirtSizeValidator::validate(...),
                        ],
                    ],
                ],
                /**
                 * Height
                 *
                 * @see https://tailwindcss.com/docs/height
                 */
                'h' => [
                    [
                        'h' => [
                            ArbitraryValueValidator::validate(...),
                            $spacing,
                            'auto',
                            'min',
                            'max',
                            'fit',
                            'svh',
                            'lvh',
                            'dvh',
                        ],
                    ],
                ],
                /**
                 * Min-Height
                 *
                 * @see https://tailwindcss.com/docs/min-height
                 */
                'min-h' => [
                    ['min-h' => [ArbitraryValueValidator::validate(...), $spacing, 'min', 'max', 'fit', 'svh', 'lvh', 'dvh']],
                ],
                /**
                 * Max-Height
                 *
                 * @see https://tailwindcss.com/docs/max-height
                 */
                'max-h' => [
                    ['max-h' => [ArbitraryValueValidator::validate(...), $spacing, 'min', 'max', 'fit', 'svh', 'lvh', 'dvh']],
                ],
                /**
                 * Size
                 *
                 * @see https://tailwindcss.com/docs/size
                 */
                'size' => [['size' => [ArbitraryValueValidator::validate(...), $spacing, 'auto', 'min', 'max', 'fit']]],
                // Typography
                /**
                 * Font Size
                 *
                 * @see https://tailwindcss.com/docs/font-size
                 */
                'font-size' => [['text' => ['base', TshirtSizeValidator::validate(...), ArbitraryLengthValidator::validate(...)]]],
                /**
                 * Font Smoothing
                 *
                 * @see https://tailwindcss.com/docs/font-smoothing
                 */
                'font-smoothing' => ['antialiased', 'subpixel-antialiased'],
                /**
                 * Font Style
                 *
                 * @see https://tailwindcss.com/docs/font-style
                 */
                'font-style' => ['italic', 'not-italic'],
                /**
                 * Font Weight
                 *
                 * @see https://tailwindcss.com/docs/font-weight
                 */
                'font-weight' => [
                    [
                        'font' => [
                            'thin',
                            'extralight',
                            'light',
                            'normal',
                            'medium',
                            'semibold',
                            'bold',
                            'extrabold',
                            'black',
                            ArbitraryNumberValidator::validate(...),
                        ],
                    ],
                ],
                /**
                 * Font Family
                 *
                 * @see https://tailwindcss.com/docs/font-family
                 */
                'font-family' => [['font' => [AnyValueValidator::validate(...)]]],
                /**
                 * Font Variant Numeric
                 *
                 * @see https://tailwindcss.com/docs/font-variant-numeric
                 */
                'fvn-normal' => ['normal-nums'],
                /**
                 * Font Variant Numeric
                 *
                 * @see https://tailwindcss.com/docs/font-variant-numeric
                 */
                'fvn-ordinal' => ['ordinal'],
                /**
                 * Font Variant Numeric
                 *
                 * @see https://tailwindcss.com/docs/font-variant-numeric
                 */
                'fvn-slashed-zero' => ['slashed-zero'],
                /**
                 * Font Variant Numeric
                 *
                 * @see https://tailwindcss.com/docs/font-variant-numeric
                 */
                'fvn-figure' => ['lining-nums', 'oldstyle-nums'],
                /**
                 * Font Variant Numeric
                 *
                 * @see https://tailwindcss.com/docs/font-variant-numeric
                 */
                'fvn-spacing' => ['proportional-nums', 'tabular-nums'],
                /**
                 * Font Variant Numeric
                 *
                 * @see https://tailwindcss.com/docs/font-variant-numeric
                 */
                'fvn-fraction' => ['diagonal-fractions', 'stacked-fractons'],
                /**
                 * Letter Spacing
                 *
                 * @see https://tailwindcss.com/docs/letter-spacing
                 */
                'tracking' => [
                    [
                        'tracking' => [
                            'tighter',
                            'tight',
                            'normal',
                            'wide',
                            'wider',
                            'widest',
                            ArbitraryValueValidator::validate(...),
                        ],
                    ],
                ],
                /**
                 * Line Clamp
                 *
                 * @see https://tailwindcss.com/docs/line-clamp
                 */
                'line-clamp' => [['line-clamp' => ['none', NumberValidator::validate(...), ArbitraryNumberValidator::validate(...)]]],
                /**
                 * Line Height
                 *
                 * @see https://tailwindcss.com/docs/line-height
                 */
                'leading' => [
                    ['leading' => [
                        'none',
                        'tight',
                        'snug',
                        'normal',
                        'relaxed',
                        'loose',
                        LengthValidator::validate(...),
                        ArbitraryValueValidator::validate(...),
                    ]],
                ],
                /**
                 * List Style Image
                 *
                 * @see https://tailwindcss.com/docs/list-style-image
                 */
                'list-image' => [['list-image' => ['none', ArbitraryValueValidator::validate(...)]]],
                /**
                 * List Style Type
                 *
                 * @see https://tailwindcss.com/docs/list-style-type
                 */
                'list-style-type' => [['list' => ['none', 'disc', 'decimal', ArbitraryValueValidator::validate(...)]]],
                /**
                 * List Style Position
                 *
                 * @see https://tailwindcss.com/docs/list-style-position
                 */
                'list-style-position' => [['list' => ['inside', 'outside']]],
                /**
                 * Placeholder Color
                 *
                 * @deprecated since Tailwind CSS v3.0.0
                 * @see https://tailwindcss.com/docs/placeholder-color
                 */
                'placeholder-color' => [['placeholder' => [$colors]]],
                /**
                 * Placeholder Opacity
                 *
                 * @see https://tailwindcss.com/docs/placeholder-opacity
                 */
                'placeholder-opacity' => [['placeholder-opacity' => [$opacity]]],
                /**
                 * Text Alignment
                 *
                 * @see https://tailwindcss.com/docs/text-align
                 */
                'text-alignment' => [['text' => ['left', 'center', 'right', 'justify', 'start', 'end']]],
                /**
                 * Text Color
                 *
                 * @see https://tailwindcss.com/docs/text-color
                 */
                'text-color' => [['text' => [$colors]]],
                /**
                 * Text Opacity
                 *
                 * @see https://tailwindcss.com/docs/text-opacity
                 */
                'text-opacity' => [['text-opacity' => [$opacity]]],
                /**
                 * Text Decoration
                 *
                 * @see https://tailwindcss.com/docs/text-decoration
                 */
                'text-decoration' => ['underline', 'overline', 'line-through', 'no-underline'],
                /**
                 * Text Decoration Style
                 *
                 * @see https://tailwindcss.com/docs/text-decoration-style
                 */
                'text-decoration-style' => [['decoration' => [...self::getLineStyles(), 'wavy']]],
                /**
                 * Text Decoration Thickness
                 *
                 * @see https://tailwindcss.com/docs/text-decoration-thickness
                 */
                'text-decoration-thickness' => [['decoration' => ['auto', 'from-font', LengthValidator::validate(...), ArbitraryLengthValidator::validate(...)]]],
                /**
                 * Text Underline Offset
                 *
                 * @see https://tailwindcss.com/docs/text-underline-offset
                 */
                'underline-offset' => [['underline-offset' => ['auto', LengthValidator::validate(...), ArbitraryValueValidator::validate(...)]]],
                /**
                 * Text Decoration Color
                 *
                 * @see https://tailwindcss.com/docs/text-decoration-color
                 */
                'text-decoration-color' => [['decoration' => [$colors]]],
                /**
                 * Text Transform
                 *
                 * @see https://tailwindcss.com/docs/text-transform
                 */
                'text-transform' => ['uppercase', 'lowercase', 'capitalize', 'normal-case'],
                /**
                 * Text Overflow
                 *
                 * @see https://tailwindcss.com/docs/text-overflow
                 */
                'text-overflow' => ['truncate', 'text-ellipsis', 'text-clip'],
                /**
                 * Text Wrap
                 *
                 * @see https://tailwindcss.com/docs/text-wrap
                 */
                'text-wrap' => [['text' => ['wrap', 'nowrap', 'balance', 'pretty']]],
                /**
                 * Text Indent
                 *
                 * @see https://tailwindcss.com/docs/text-indent
                 */
                'indent' => [['indent' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Vertical Alignment
                 *
                 * @see https://tailwindcss.com/docs/vertical-align
                 */
                'vertical-align' => [
                    [
                        'align' => [
                            'baseline',
                            'top',
                            'middle',
                            'bottom',
                            'text-top',
                            'text-bottom',
                            'sub',
                            'super',
                            ArbitraryValueValidator::validate(...),
                        ],
                    ],
                ],
                /**
                 * Whitespace
                 *
                 * @see https://tailwindcss.com/docs/whitespace
                 */
                'whitespace' => [
                    ['whitespace' => ['normal', 'nowrap', 'pre', 'pre-line', 'pre-wrap', 'break-spaces']],
                ],
                /**
                 * Word Break
                 *
                 * @see https://tailwindcss.com/docs/word-break
                 */
                'break' => [['break' => ['normal', 'words', 'all', 'keep']]],
                /**
                 * Hyphens
                 *
                 * @see https://tailwindcss.com/docs/hyphens
                 */
                'hyphens' => [['hyphens' => ['none', 'manual', 'auto']]],
                /**
                 * Content
                 *
                 * @see https://tailwindcss.com/docs/content
                 */
                'content' => [['content' => ['none', ArbitraryValueValidator::validate(...)]]],
                // Backgrounds
                /**
                 * Background Attachment
                 *
                 * @see https://tailwindcss.com/docs/background-attachment
                 */
                'bg-attachment' => [['bg' => ['fixed', 'local', 'scroll']]],
                /**
                 * Background Clip
                 *
                 * @see https://tailwindcss.com/docs/background-clip
                 */
                'bg-clip' => [['bg-clip' => ['border', 'padding', 'content', 'text']]],
                /**
                 * Background Opacity
                 *
                 * @deprecated since Tailwind CSS v3.0.0
                 * @see https://tailwindcss.com/docs/background-opacity
                 */
                'bg-opacity' => [['bg-opacity' => [$opacity]]],
                /**
                 * Background Origin
                 *
                 * @see https://tailwindcss.com/docs/background-origin
                 */
                'bg-origin' => [['bg-origin' => ['border', 'padding', 'content']]],
                /**
                 * Background Position
                 *
                 * @see https://tailwindcss.com/docs/background-position
                 */
                'bg-position' => [['bg' => [...self::getPositions(), ArbitraryPositionValidator::validate(...)]]],
                /**
                 * Background Repeat
                 *
                 * @see https://tailwindcss.com/docs/background-repeat
                 */
                'bg-repeat' => [['bg' => ['no-repeat', ['repeat' => ['', 'x', 'y', 'round', 'space']]]]],
                /**
                 * Background Size
                 *
                 * @see https://tailwindcss.com/docs/background-size
                 */
                'bg-size' => [['bg' => ['auto', 'cover', 'contain', ArbitrarySizeValidator::validate(...)]]],
                /**
                 * Background Image
                 *
                 * @see https://tailwindcss.com/docs/background-image
                 */
                'bg-image' => [
                    [
                        'bg' => [
                            'none',
                            ['gradient-to' => ['t', 'tr', 'r', 'br', 'b', 'bl', 'l', 'tl']],
                            ArbitraryImageValidator::validate(...),
                        ],
                    ],
                ],
                /**
                 * Background Color
                 *
                 * @see https://tailwindcss.com/docs/background-color
                 */
                'bg-color' => [['bg' => [$colors]]],
                /**
                 * Gradient Color Stops From Position
                 *
                 * @see https://tailwindcss.com/docs/gradient-color-stops
                 */
                'gradient-from-pos' => [['from' => [$gradientColorStopPositions]]],
                /**
                 * Gradient Color Stops Via Position
                 *
                 * @see https://tailwindcss.com/docs/gradient-color-stops
                 */
                'gradient-via-pos' => [['via' => [$gradientColorStopPositions]]],
                /**
                 * Gradient Color Stops To Position
                 *
                 * @see https://tailwindcss.com/docs/gradient-color-stops
                 */
                'gradient-to-pos' => [['to' => [$gradientColorStopPositions]]],
                /**
                 * Gradient Color Stops From
                 *
                 * @see https://tailwindcss.com/docs/gradient-color-stops
                 */
                'gradient-from' => [['from' => [$gradientColorStops]]],
                /**
                 * Gradient Color Stops Via
                 *
                 * @see https://tailwindcss.com/docs/gradient-color-stops
                 */
                'gradient-via' => [['via' => [$gradientColorStops]]],
                /**
                 * Gradient Color Stops To
                 *
                 * @see https://tailwindcss.com/docs/gradient-color-stops
                 */
                'gradient-to' => [['to' => [$gradientColorStops]]],
                // Borders
                /**
                 * Border Radius
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded' => [['rounded' => [$borderRadius]]],
                /**
                 * Border Radius Start
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-s' => [['rounded-s' => [$borderRadius]]],
                /**
                 * Border Radius End
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-e' => [['rounded-e' => [$borderRadius]]],
                /**
                 * Border Radius Top
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-t' => [['rounded-t' => [$borderRadius]]],
                /**
                 * Border Radius Right
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-r' => [['rounded-r' => [$borderRadius]]],
                /**
                 * Border Radius Bottom
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-b' => [['rounded-b' => [$borderRadius]]],
                /**
                 * Border Radius Left
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-l' => [['rounded-l' => [$borderRadius]]],
                /**
                 * Border Radius Start Start
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-ss' => [['rounded-ss' => [$borderRadius]]],
                /**
                 * Border Radius Start End
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-se' => [['rounded-se' => [$borderRadius]]],
                /**
                 * Border Radius End End
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-ee' => [['rounded-ee' => [$borderRadius]]],
                /**
                 * Border Radius End Start
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-es' => [['rounded-es' => [$borderRadius]]],
                /**
                 * Border Radius Top Left
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-tl' => [['rounded-tl' => [$borderRadius]]],
                /**
                 * Border Radius Top Right
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-tr' => [['rounded-tr' => [$borderRadius]]],
                /**
                 * Border Radius Bottom Right
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-br' => [['rounded-br' => [$borderRadius]]],
                /**
                 * Border Radius Bottom Left
                 *
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-bl' => [['rounded-bl' => [$borderRadius]]],
                /**
                 * Border Width
                 *
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w' => [['border' => [$borderWidth]]],
                /**
                 * Border Width X
                 *
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-x' => [['border-x' => [$borderWidth]]],
                /**
                 * Border Width Y
                 *
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-y' => [['border-y' => [$borderWidth]]],
                /**
                 * Border Width Start
                 *
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-s' => [['border-s' => [$borderWidth]]],
                /**
                 * Border Width End
                 *
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-e' => [['border-e' => [$borderWidth]]],
                /**
                 * Border Width Top
                 *
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-t' => [['border-t' => [$borderWidth]]],
                /**
                 * Border Width Right
                 *
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-r' => [['border-r' => [$borderWidth]]],
                /**
                 * Border Width Bottom
                 *
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-b' => [['border-b' => [$borderWidth]]],
                /**
                 * Border Width Left
                 *
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-l' => [['border-l' => [$borderWidth]]],
                /**
                 * Border Opacity
                 *
                 * @see https://tailwindcss.com/docs/border-opacity
                 */
                'border-opacity' => [['border-opacity' => [$opacity]]],
                /**
                 * Border Style
                 *
                 * @see https://tailwindcss.com/docs/border-style
                 */
                'border-style' => [['border' => [...self::getLineStyles(), 'hidden']]],
                /**
                 * Divide Width X
                 *
                 * @see https://tailwindcss.com/docs/divide-width
                 */
                'divide-x' => [['divide-x' => [$borderWidth]]],
                /**
                 * Divide Width X Reverse
                 *
                 * @see https://tailwindcss.com/docs/divide-width
                 */
                'divide-x-reverse' => ['divide-x-reverse'],
                /**
                 * Divide Width Y
                 *
                 * @see https://tailwindcss.com/docs/divide-width
                 */
                'divide-y' => [['divide-y' => [$borderWidth]]],
                /**
                 * Divide Width Y Reverse
                 *
                 * @see https://tailwindcss.com/docs/divide-width
                 */
                'divide-y-reverse' => ['divide-y-reverse'],
                /**
                 * Divide Opacity
                 *
                 * @see https://tailwindcss.com/docs/divide-opacity
                 */
                'divide-opacity' => [['divide-opacity' => [$opacity]]],
                /**
                 * Divide Style
                 *
                 * @see https://tailwindcss.com/docs/divide-style
                 */
                'divide-style' => [['divide' => self::getLineStyles()]],
                /**
                 * Border Color
                 *
                 * @see https://tailwindcss.com/docs/border-color
                 */
                'border-color' => [['border' => [$borderColor]]],
                /**
                 * Border Color X
                 *
                 * @see https://tailwindcss.com/docs/border-color
                 */
                'border-color-x' => [['border-x' => [$borderColor]]],
                /**
                 * Border Color Y
                 *
                 * @see https://tailwindcss.com/docs/border-color
                 */
                'border-color-y' => [['border-y' => [$borderColor]]],
                /**
                 * Border Color Top
                 *
                 * @see https://tailwindcss.com/docs/border-color
                 */
                'border-color-t' => [['border-t' => [$borderColor]]],
                /**
                 * Border Color Right
                 *
                 * @see https://tailwindcss.com/docs/border-color
                 */
                'border-color-r' => [['border-r' => [$borderColor]]],
                /**
                 * Border Color Bottom
                 *
                 * @see https://tailwindcss.com/docs/border-color
                 */
                'border-color-b' => [['border-b' => [$borderColor]]],
                /**
                 * Border Color Left
                 *
                 * @see https://tailwindcss.com/docs/border-color
                 */
                'border-color-l' => [['border-l' => [$borderColor]]],
                /**
                 * Divide Color
                 *
                 * @see https://tailwindcss.com/docs/divide-color
                 */
                'divide-color' => [['divide' => [$borderColor]]],
                /**
                 * Outline Style
                 *
                 * @see https://tailwindcss.com/docs/outline-style
                 */
                'outline-style' => [['outline' => ['', ...self::getLineStyles()]]],
                /**
                 * Outline Offset
                 *
                 * @see https://tailwindcss.com/docs/outline-offset
                 */
                'outline-offset' => [['outline-offset' => [LengthValidator::validate(...), ArbitraryValueValidator::validate(...)]]],
                /**
                 * Outline Width
                 *
                 * @see https://tailwindcss.com/docs/outline-width
                 */
                'outline-w' => [['outline' => [LengthValidator::validate(...), ArbitraryLengthValidator::validate(...)]]],
                /**
                 * Outline Color
                 *
                 * @see https://tailwindcss.com/docs/outline-color
                 */
                'outline-color' => [['outline' => [$colors]]],
                /**
                 * Ring Width
                 *
                 * @see https://tailwindcss.com/docs/ring-width
                 */
                'ring-w' => [['ring' => self::getLengthWithEmptyAndArbitrary()]],
                /**
                 * Ring Width Inset
                 *
                 * @see https://tailwindcss.com/docs/ring-width
                 */
                'ring-w-inset' => ['ring-inset'],
                /**
                 * Ring Color
                 *
                 * @see https://tailwindcss.com/docs/ring-color
                 */
                'ring-color' => [['ring' => [$colors]]],
                /**
                 * Ring Opacity
                 *
                 * @see https://tailwindcss.com/docs/ring-opacity
                 */
                'ring-opacity' => [['ring-opacity' => [$opacity]]],
                /**
                 * Ring Offset Width
                 *
                 * @see https://tailwindcss.com/docs/ring-offset-width
                 */
                'ring-offset-w' => [['ring-offset' => [LengthValidator::validate(...), ArbitraryLengthValidator::validate(...)]]],
                /**
                 * Ring Offset Color
                 *
                 * @see https://tailwindcss.com/docs/ring-offset-color
                 */
                'ring-offset-color' => [['ring-offset' => [$colors]]],
                // Effects
                /**
                 * Box Shadow
                 *
                 * @see https://tailwindcss.com/docs/box-shadow
                 */
                'shadow' => [['shadow' => ['', 'inner', 'none', TshirtSizeValidator::validate(...), ArbitraryShadowValidator::validate(...)]]],
                /**
                 * Box Shadow Color
                 *
                 * @see https://tailwindcss.com/docs/box-shadow-color
                 */
                'shadow-color' => [['shadow' => [AnyValueValidator::validate(...)]]],
                /**
                 * Opacity
                 *
                 * @see https://tailwindcss.com/docs/opacity
                 */
                'opacity' => [['opacity' => [$opacity]]],
                /**
                 * Mix Blend Mode
                 *
                 * @see https://tailwindcss.com/docs/mix-blend-mode
                 */
                'mix-blend' => [['mix-blend' => self::getBlendModes()]],
                /**
                 * Background Blend Mode
                 *
                 * @see https://tailwindcss.com/docs/background-blend-mode
                 */
                'bg-blend' => [['bg-blend' => self::getBlendModes()]],
                // Filters
                /**
                 * Filter
                 *
                 * @deprecated since Tailwind CSS v3.0.0
                 * @see https://tailwindcss.com/docs/filter
                 */
                'filter' => [['filter' => ['', 'none']]],
                /**
                 * Blur
                 *
                 * @see https://tailwindcss.com/docs/blur
                 */
                'blur' => [['blur' => [$blur]]],
                /**
                 * Brightness
                 *
                 * @see https://tailwindcss.com/docs/brightness
                 */
                'brightness' => [['brightness' => [$brightness]]],
                /**
                 * Contrast
                 *
                 * @see https://tailwindcss.com/docs/contrast
                 */
                'contrast' => [['contrast' => [$contrast]]],
                /**
                 * Drop Shadow
                 *
                 * @see https://tailwindcss.com/docs/drop-shadow
                 */
                'drop-shadow' => [['drop-shadow' => ['', 'none', TshirtSizeValidator::validate(...), ArbitraryValueValidator::validate(...)]]],
                /**
                 * Grayscale
                 *
                 * @see https://tailwindcss.com/docs/grayscale
                 */
                'grayscale' => [['grayscale' => [$grayscale]]],
                /**
                 * Hue Rotate
                 *
                 * @see https://tailwindcss.com/docs/hue-rotate
                 */
                'hue-rotate' => [['hue-rotate' => [$hueRotate]]],
                /**
                 * Invert
                 *
                 * @see https://tailwindcss.com/docs/invert
                 */
                'invert' => [['invert' => [$invert]]],
                /**
                 * Saturate
                 *
                 * @see https://tailwindcss.com/docs/saturate
                 */
                'saturate' => [['saturate' => [$saturate]]],
                /**
                 * Sepia
                 *
                 * @see https://tailwindcss.com/docs/sepia
                 */
                'sepia' => [['sepia' => [$sepia]]],
                /**
                 * Backdrop Filter
                 *
                 * @deprecated since Tailwind CSS v3.0.0
                 * @see https://tailwindcss.com/docs/backdrop-filter
                 */
                'backdrop-filter' => [['backdrop-filter' => ['', 'none']]],
                /**
                 * Backdrop Blur
                 *
                 * @see https://tailwindcss.com/docs/backdrop-blur
                 */
                'backdrop-blur' => [['backdrop-blur' => [$blur]]],
                /**
                 * Backdrop Brightness
                 *
                 * @see https://tailwindcss.com/docs/backdrop-brightness
                 */
                'backdrop-brightness' => [['backdrop-brightness' => [$brightness]]],
                /**
                 * Backdrop Contrast
                 *
                 * @see https://tailwindcss.com/docs/backdrop-contrast
                 */
                'backdrop-contrast' => [['backdrop-contrast' => [$contrast]]],
                /**
                 * Backdrop Grayscale
                 *
                 * @see https://tailwindcss.com/docs/backdrop-grayscale
                 */
                'backdrop-grayscale' => [['backdrop-grayscale' => [$grayscale]]],
                /**
                 * Backdrop Hue Rotate
                 *
                 * @see https://tailwindcss.com/docs/backdrop-hue-rotate
                 */
                'backdrop-hue-rotate' => [['backdrop-hue-rotate' => [$hueRotate]]],
                /**
                 * Backdrop Invert
                 *
                 * @see https://tailwindcss.com/docs/backdrop-invert
                 */
                'backdrop-invert' => [['backdrop-invert' => [$invert]]],
                /**
                 * Backdrop Opacity
                 *
                 * @see https://tailwindcss.com/docs/backdrop-opacity
                 */
                'backdrop-opacity' => [['backdrop-opacity' => [$opacity]]],
                /**
                 * Backdrop Saturate
                 *
                 * @see https://tailwindcss.com/docs/backdrop-saturate
                 */
                'backdrop-saturate' => [['backdrop-saturate' => [$saturate]]],
                /**
                 * Backdrop Sepia
                 *
                 * @see https://tailwindcss.com/docs/backdrop-sepia
                 */
                'backdrop-sepia' => [['backdrop-sepia' => [$sepia]]],
                // Tables
                /**
                 * Border Collapse
                 *
                 * @see https://tailwindcss.com/docs/border-collapse
                 */
                'border-collapse' => [['border' => ['collapse', 'separate']]],
                /**
                 * Border Spacing
                 *
                 * @see https://tailwindcss.com/docs/border-spacing
                 */
                'border-spacing' => [['border-spacing' => [$borderSpacing]]],
                /**
                 * Border Spacing X
                 *
                 * @see https://tailwindcss.com/docs/border-spacing
                 */
                'border-spacing-x' => [['border-spacing-x' => [$borderSpacing]]],
                /**
                 * Border Spacing Y
                 *
                 * @see https://tailwindcss.com/docs/border-spacing
                 */
                'border-spacing-y' => [['border-spacing-y' => [$borderSpacing]]],
                /**
                 * Table Layout
                 *
                 * @see https://tailwindcss.com/docs/table-layout
                 */
                'table-layout' => [['table' => ['auto', 'fixed']]],
                /**
                 * Caption Side
                 *
                 * @see https://tailwindcss.com/docs/caption-side
                 */
                'caption' => [['caption' => ['top', 'bottom']]],
                // Transitions and Animation
                /**
                 * Tranisition Property
                 *
                 * @see https://tailwindcss.com/docs/transition-property
                 */
                'transition' => [
                    [
                        'transition' => [
                            'none',
                            'all',
                            '',
                            'colors',
                            'opacity',
                            'shadow',
                            'transform',
                            ArbitraryValueValidator::validate(...),
                        ],
                    ],
                ],
                /**
                 * Transition Duration
                 *
                 * @see https://tailwindcss.com/docs/transition-duration
                 */
                'duration' => [['duration' => self::getNumberAndArbitrary()]],
                /**
                 * Transition Timing Function
                 *
                 * @see https://tailwindcss.com/docs/transition-timing-function
                 */
                'ease' => [['ease' => ['linear', 'in', 'out', 'in-out', ArbitraryValueValidator::validate(...)]]],
                /**
                 * Transition Delay
                 *
                 * @see https://tailwindcss.com/docs/transition-delay
                 */
                'delay' => [['delay' => self::getNumberAndArbitrary()]],
                /**
                 * Animation
                 *
                 * @see https://tailwindcss.com/docs/animation
                 */
                'animate' => [['animate' => ['none', 'spin', 'ping', 'pulse', 'bounce', ArbitraryValueValidator::validate(...)]]],
                // Transforms
                /**
                 * Transform
                 *
                 * @see https://tailwindcss.com/docs/transform
                 */
                'transform' => [['transform' => ['', 'gpu', 'none']]],
                /**
                 * Scale
                 *
                 * @see https://tailwindcss.com/docs/scale
                 */
                'scale' => [['scale' => [$scale]]],
                /**
                 * Scale X
                 *
                 * @see https://tailwindcss.com/docs/scale
                 */
                'scale-x' => [['scale-x' => [$scale]]],
                /**
                 * Scale Y
                 *
                 * @see https://tailwindcss.com/docs/scale
                 */
                'scale-y' => [['scale-y' => [$scale]]],
                /**
                 * Rotate
                 *
                 * @see https://tailwindcss.com/docs/rotate
                 */
                'rotate' => [['rotate' => [IntegerValidator::validate(...), ArbitraryValueValidator::validate(...)]]],
                /**
                 * Translate X
                 *
                 * @see https://tailwindcss.com/docs/translate
                 */
                'translate-x' => [['translate-x' => [$translate]]],
                /**
                 * Translate Y
                 *
                 * @see https://tailwindcss.com/docs/translate
                 */
                'translate-y' => [['translate-y' => [$translate]]],
                /**
                 * Skew X
                 *
                 * @see https://tailwindcss.com/docs/skew
                 */
                'skew-x' => [['skew-x' => [$skew]]],
                /**
                 * Skew Y
                 *
                 * @see https://tailwindcss.com/docs/skew
                 */
                'skew-y' => [['skew-y' => [$skew]]],
                /**
                 * Transform Origin
                 *
                 * @see https://tailwindcss.com/docs/transform-origin
                 */
                'transform-origin' => [
                    [
                        'origin' => [
                            'center',
                            'top',
                            'top-right',
                            'right',
                            'bottom-right',
                            'bottom',
                            'bottom-left',
                            'left',
                            'top-left',
                            ArbitraryValueValidator::validate(...),
                        ],
                    ],
                ],
                // Interactivity
                /**
                 * Accent Color
                 *
                 * @see https://tailwindcss.com/docs/accent-color
                 */
                'accent' => [['accent' => ['auto', $colors]]],
                /**
                 * Appearance
                 *
                 * @see https://tailwindcss.com/docs/appearance
                 */
                'appearance' => [['appearance' => ['none', 'auto']]],
                /**
                 * Cursor
                 *
                 * @see https://tailwindcss.com/docs/cursor
                 */
                'cursor' => [
                    [
                        'cursor' => [
                            'auto',
                            'default',
                            'pointer',
                            'wait',
                            'text',
                            'move',
                            'help',
                            'not-allowed',
                            'none',
                            'context-menu',
                            'progress',
                            'cell',
                            'crosshair',
                            'vertical-text',
                            'alias',
                            'copy',
                            'no-drop',
                            'grab',
                            'grabbing',
                            'all-scroll',
                            'col-resize',
                            'row-resize',
                            'n-resize',
                            'e-resize',
                            's-resize',
                            'w-resize',
                            'ne-resize',
                            'nw-resize',
                            'se-resize',
                            'sw-resize',
                            'ew-resize',
                            'ns-resize',
                            'nesw-resize',
                            'nwse-resize',
                            'zoom-in',
                            'zoom-out',
                            ArbitraryValueValidator::validate(...),
                        ],
                    ],
                ],
                /**
                 * Caret Color
                 *
                 * @see https://tailwindcss.com/docs/just-in-time-mode#caret-color-utilities
                 */
                'caret-color' => [['caret' => [$colors]]],
                /**
                 * Pointer Events
                 *
                 * @see https://tailwindcss.com/docs/pointer-events
                 */
                'pointer-events' => [['pointer-events' => ['none', 'auto']]],
                /**
                 * Resize
                 *
                 * @see https://tailwindcss.com/docs/resize
                 */
                'resize' => [['resize' => ['none', 'y', 'x', '']]],
                /**
                 * Scroll Behavior
                 *
                 * @see https://tailwindcss.com/docs/scroll-behavior
                 */
                'scroll-behavior' => [['scroll' => ['auto', 'smooth']]],
                /**
                 * Scroll Margin
                 *
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-m' => [['scroll-m' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Margin X
                 *
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-mx' => [['scroll-mx' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Margin Y
                 *
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-my' => [['scroll-my' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Margin Start
                 *
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-ms' => [['scroll-ms' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Margin End
                 *
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-me' => [['scroll-me' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Margin Top
                 *
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-mt' => [['scroll-mt' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Margin Right
                 *
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-mr' => [['scroll-mr' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Margin Bottom
                 *
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-mb' => [['scroll-mb' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Margin Left
                 *
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-ml' => [['scroll-ml' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Padding
                 *
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-p' => [['scroll-p' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Padding X
                 *
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-px' => [['scroll-px' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Padding Y
                 *
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-py' => [['scroll-py' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Padding Start
                 *
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-ps' => [['scroll-ps' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Padding End
                 *
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-pe' => [['scroll-pe' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Padding Top
                 *
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-pt' => [['scroll-pt' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Padding Right
                 *
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-pr' => [['scroll-pr' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Padding Bottom
                 *
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-pb' => [['scroll-pb' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Padding Left
                 *
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-pl' => [['scroll-pl' => self::getSpacingWithArbitrary($spacing)]],
                /**
                 * Scroll Snap Align
                 *
                 * @see https://tailwindcss.com/docs/scroll-snap-align
                 */
                'snap-align' => [['snap' => ['start', 'end', 'center', 'align-none']]],
                /**
                 * Scroll Snap Stop
                 *
                 * @see https://tailwindcss.com/docs/scroll-snap-stop
                 */
                'snap-stop' => [['snap' => ['normal', 'always']]],
                /**
                 * Scroll Snap Type
                 *
                 * @see https://tailwindcss.com/docs/scroll-snap-type
                 */
                'snap-type' => [['snap' => ['none', 'x', 'y', 'both']]],
                /**
                 * Scroll Snap Type Strictness
                 *
                 * @see https://tailwindcss.com/docs/scroll-snap-type
                 */
                'snap-strictness' => [['snap' => ['mandatory', 'proximity']]],
                /**
                 * Touch Action
                 *
                 * @see https://tailwindcss.com/docs/touch-action
                 */
                'touch' => [
                    [
                        'touch' => [
                            'auto',
                            'none',
                            'manipulation',
                        ],
                    ],
                ],
                /**
                 * Touch Action X
                 *
                 * @see https://tailwindcss.com/docs/touch-action
                 */
                'touch-x' => [
                    [
                        'touch-pan' => ['x', 'left', 'right'],
                    ],
                ],
                /**
                 * Touch Action Y
                 *
                 * @see https://tailwindcss.com/docs/touch-action
                 */
                'touch-y' => [
                    [
                        'touch-pan' => ['y', 'up', 'down'],
                    ],
                ],
                /**
                 * Touch Action Pinch Zoom
                 *
                 * @see https://tailwindcss.com/docs/touch-action
                 */
                'touch-pz' => ['touch-pinch-zoom'],
                /**
                 * User Select
                 *
                 * @see https://tailwindcss.com/docs/user-select
                 */
                'select' => [['select' => ['none', 'text', 'all', 'auto']]],
                /**
                 * Will Change
                 *
                 * @see https://tailwindcss.com/docs/will-change
                 */
                'will-change' => [
                    ['will-change' => ['auto', 'scroll', 'contents', 'transform', ArbitraryValueValidator::validate(...)]],
                ],
                // SVG
                /**
                 * Fill
                 *
                 * @see https://tailwindcss.com/docs/fill
                 */
                'fill' => [['fill' => [$colors, 'none']]],
                /**
                 * Stroke Width
                 *
                 * @see https://tailwindcss.com/docs/stroke-width
                 */
                'stroke-w' => [['stroke' => [LengthValidator::validate(...), ArbitraryLengthValidator::validate(...), ArbitraryNumberValidator::validate(...)]]],
                /**
                 * Stroke
                 *
                 * @see https://tailwindcss.com/docs/stroke
                 */
                'stroke' => [['stroke' => [$colors, 'none']]],
                // Accessibility
                /**
                 * Screen Readers
                 *
                 * @see https://tailwindcss.com/docs/screen-readers
                 */
                'sr' => ['sr-only', 'not-sr-only'],
                /**
                 * Forced Color Adjust
                 *
                 * @see https://tailwindcss.com/docs/forced-color-adjust
                 */
                'forced-color-adjust' => [['forced-color-adjust' => ['auto', 'none']]],
            ],
            'conflictingClassGroups' => [
                'overflow' => ['overflow-x', 'overflow-y'],
                'overscroll' => ['overscroll-x', 'overscroll-y'],
                'inset' => ['inset-x', 'inset-y', 'start', 'end', 'top', 'right', 'bottom', 'left'],
                'inset-x' => ['right', 'left'],
                'inset-y' => ['top', 'bottom'],
                'flex' => ['basis', 'grow', 'shrink'],
                'gap' => ['gap-x', 'gap-y'],
                'p' => ['px', 'py', 'ps', 'pe', 'pt', 'pr', 'pb', 'pl'],
                'px' => ['pr', 'pl'],
                'py' => ['pt', 'pb'],
                'm' => ['mx', 'my', 'ms', 'me', 'mt', 'mr', 'mb', 'ml'],
                'mx' => ['mr', 'ml'],
                'my' => ['mt', 'mb'],
                'size' => ['w', 'h'],
                'font-size' => ['leading'],
                'fvn-normal' => [
                    'fvn-ordinal',
                    'fvn-slashed-zero',
                    'fvn-figure',
                    'fvn-spacing',
                    'fvn-fraction',
                ],
                'fvn-ordinal' => ['fvn-normal'],
                'fvn-slashed-zero' => ['fvn-normal'],
                'fvn-figure' => ['fvn-normal'],
                'fvn-spacing' => ['fvn-normal'],
                'fvn-fraction' => ['fvn-normal'],
                'line-clamp' => ['display', 'overflow'],
                'rounded' => [
                    'rounded-s',
                    'rounded-e',
                    'rounded-t',
                    'rounded-r',
                    'rounded-b',
                    'rounded-l',
                    'rounded-ss',
                    'rounded-se',
                    'rounded-ee',
                    'rounded-es',
                    'rounded-tl',
                    'rounded-tr',
                    'rounded-br',
                    'rounded-bl',
                ],
                'rounded-s' => ['rounded-ss', 'rounded-es'],
                'rounded-e' => ['rounded-se', 'rounded-ee'],
                'rounded-t' => ['rounded-tl', 'rounded-tr'],
                'rounded-r' => ['rounded-tr', 'rounded-br'],
                'rounded-b' => ['rounded-br', 'rounded-bl'],
                'rounded-l' => ['rounded-tl', 'rounded-bl'],
                'border-spacing' => ['border-spacing-x', 'border-spacing-y'],
                'border-w' => [
                    'border-w-s',
                    'border-w-e',
                    'border-w-t',
                    'border-w-r',
                    'border-w-b',
                    'border-w-l',
                ],
                'border-w-x' => ['border-w-r', 'border-w-l'],
                'border-w-y' => ['border-w-t', 'border-w-b'],
                'border-color' => [
                    'border-color-t',
                    'border-color-r',
                    'border-color-b',
                    'border-color-l',
                ],
                'border-color-x' => ['border-color-r', 'border-color-l'],
                'border-color-y' => ['border-color-t', 'border-color-b'],
                'scroll-m' => [
                    'scroll-mx',
                    'scroll-my',
                    'scroll-ms',
                    'scroll-me',
                    'scroll-mt',
                    'scroll-mr',
                    'scroll-mb',
                    'scroll-ml',
                ],
                'scroll-mx' => ['scroll-mr', 'scroll-ml'],
                'scroll-my' => ['scroll-mt', 'scroll-mb'],
                'scroll-p' => [
                    'scroll-px',
                    'scroll-py',
                    'scroll-ps',
                    'scroll-pe',
                    'scroll-pt',
                    'scroll-pr',
                    'scroll-pb',
                    'scroll-pl',
                ],
                'scroll-px' => ['scroll-pr', 'scroll-pl'],
                'scroll-py' => ['scroll-pt', 'scroll-pb'],
                'touch' => ['touch-x', 'touch-y', 'touch-pz'],
                'touch-x' => ['touch'],
                'touch-y' => ['touch'],
                'touch-pz' => ['touch'],
            ],
            'conflictingClassGroupModifiers' => [
                'font-size' => ['leading'],
            ],
        ];
    }

    public static function fromTheme(string $key): ThemeGetter
    {
        return new ThemeGetter($key);
    }

    /**
     * @return array<int, callable>
     */
    private static function getNumber(): array
    {
        return [
            NumberValidator::validate(...),
            ArbitraryNumberValidator::validate(...),
        ];
    }

    /**
     * @return array<int, string|callable>
     */
    private static function getLengthWithEmptyAndArbitrary(): array
    {
        return [
            '',
            LengthValidator::validate(...),
            ArbitraryLengthValidator::validate(...),
        ];
    }

    /**
     * @return array<int, string|callable>
     */
    private static function getZeroAndEmpty(): array
    {
        return [
            '',
            '0',
            ArbitraryValueValidator::validate(...),
        ];
    }

    /**
     * @return array<int, callable>
     */
    private static function getNumberAndArbitrary(): array
    {
        return [NumberValidator::validate(...), ArbitraryValueValidator::validate(...)];
    }

    /**
     * @return array<int, string|callable|ThemeGetter>
     */
    private static function getSpacingWithAutoAndArbitrary(ThemeGetter $spacing): array
    {
        return [
            'auto',
            ArbitraryValueValidator::validate(...),
            $spacing,
        ];
    }

    /**
     * @return array<int, callable|ThemeGetter>
     */
    private static function getSpacingWithArbitrary(ThemeGetter $spacing): array
    {
        return [
            ArbitraryValueValidator::validate(...),
            $spacing,
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function getBreaks(): array
    {
        return [
            'auto',
            'avoid',
            'all',
            'avoid-page',
            'page',
            'left',
            'right',
            'column',
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function getPositions(): array
    {
        return [
            'bottom',
            'center',
            'left',
            'left-bottom',
            'left-top',
            'right',
            'right-bottom',
            'right-top',
            'top',
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function getOverflow(): array
    {
        return [
            'auto',
            'hidden',
            'clip',
            'visible',
            'scroll',
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function getOverscroll(): array
    {
        return [
            'auto',
            'contain',
            'none',
        ];
    }

    /**
     * @return array<int, string|callable>
     */
    private static function getNumberWithAutoAndArbitrary(): array
    {
        return [
            'auto',
            NumberValidator::validate(...),
            ArbitraryValueValidator::validate(...),
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function getAlign(): array
    {
        return [
            'start',
            'end',
            'center',
            'between',
            'around',
            'evenly',
            'stretch',
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function getLineStyles(): array
    {
        return [
            'solid',
            'dashed',
            'dotted',
            'double',
            'none',
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function getBlendModes(): array
    {
        return [
            'normal',
            'multiply',
            'screen',
            'overlay',
            'darken',
            'lighten',
            'color-dodge',
            'color-burn',
            'hard-light',
            'soft-light',
            'difference',
            'exclusion',
            'hue',
            'saturation',
            'color',
            'luminosity',
            'plus-lighter',
        ];
    }
}
