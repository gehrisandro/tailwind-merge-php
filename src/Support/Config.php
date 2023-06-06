<?php

namespace TailwindMerge\Support;

use Illuminate\Support\Str;
use TailwindMerge\ValueObjects\ThemeGetter;

class Config
{
    const FRACTION_REGEX = '/^\d+\/\d+$/';
    const LENGTH_UNIT_REGEX = '/\d+(%|px|r?em|[sdl]?v([hwib]|min|max)|pt|pc|in|cm|mm|cap|ch|ex|r?lh|cq(w|h|i|b|min|max))|^0$/';
    const ARBITRARY_VALUE_REGEX = '/^\[(?:([a-z-]+):)?(.+)\]$/i';
    const T_SHIRT_UNIT_REGEX = '/^(\d+(\.\d+)?)?(xs|sm|md|lg|xl)$/';
    const SHADOW_REGEX = '/^-?((\d+)?\.?(\d+)[a-z]+|0)_-?((\d+)?\.?(\d+)[a-z]+|0)/';

    public static function getDefaultConfig()
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

        $getOverscroll = fn() => ['auto', 'contain', 'none'];
        $getOverflow = fn() => ['auto', 'hidden', 'clip', 'visible', 'scroll'];
        $getSpacingWithAuto = fn() => ['auto', $spacing];
        $getLengthWithEmpty = fn() => ['', self::isLength(...)];
        $getNumberWithAutoAndArbitrary = fn() => ['auto', self::isNumber(...), self::isArbitraryValue(...)];
        $getPositions = fn() => [
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
        $getLineStyles = fn() => ['solid', 'dashed', 'dotted', 'double', 'none'];
        $getBlendModes = fn() => [
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
        $getAlign = fn() => ['start', 'end', 'center', 'between', 'around', 'evenly', 'stretch'];
        $getZeroAndEmpty = fn() => ['', '0', self::isArbitraryValue(...)];
        $getBreaks = fn() => ['auto', 'avoid', 'all', 'avoid-page', 'page', 'left', 'right', 'column'];
        $getNumber = fn() => [self::isNumber(...), self::isArbitraryNumber(...)];
        $getNumberAndArbitrary = fn() => [self::isNumber(...), self::isArbitraryValue(...)];

        return [
            'cacheSize' => 500,
            'theme' => [
                'colors' => [self::isAny(...)],
                'spacing' => [self::isLength(...)],
                'blur' => ['none', '', self::isTshirtSize(...), self::isArbitraryLength(...)],
                'brightness' => self::getNumber(),
                'borderColor' => [$colors],
                'borderRadius' => ['none', '', 'full', self::isTshirtSize(...), self::isArbitraryLength(...)],
                'borderSpacing' => [$spacing],
                'borderWidth' => self::getLengthWithEmpty(),
                'contrast' => self::getNumber(),
                'grayscale' => self::getZeroAndEmpty(),
                'hueRotate' => self::getNumberAndArbitrary(),
                'invert' => self::getZeroAndEmpty(),
                'gap' => [$spacing],
                'gradientColorStops' => [$colors],
                'gradientColorStopPositions' => [self::isPercent(...), self::isArbitraryLength(...)],
                'inset' => self::getSpacingWithAuto($spacing),
                'margin' => self::getSpacingWithAuto($spacing),
                'opacity' => self::getNumber(),
                'padding' => [$spacing],
                'saturate' => self::getNumber(),
                'scale' => self::getNumber(),
                'sepia' => self::getZeroAndEmpty(),
                'skew' => self::getNumberAndArbitrary(),
                'space' => [$spacing],
                'translate' => [$spacing],
            ],
            'classGroups' => [
                // Layout
                /**
                 * Aspect Ratio
                 * @see https://tailwindcss.com/docs/aspect-ratio
                 */
                'aspect' => [['aspect' => ['auto', 'square', 'video', self::isArbitraryValue(...)]]],
                /**
                 * Container
                 * @see https://tailwindcss.com/docs/container
                 */
                'container' => ['container'],
                /**
                 * Columns
                 * @see https://tailwindcss.com/docs/columns
                 */
                'columns' => [['columns' => [self::isTshirtSize(...)]]],
                /**
                 * Break After
                 * @see https://tailwindcss.com/docs/break-after
                 */
                'break-after' => [['break-after' => self::getBreaks()]],
                /**
                 * Break Before
                 * @see https://tailwindcss.com/docs/break-before
                 */
                'break-before' => [['break-before' => self::getBreaks()]],
                /**
                 * Break Inside
                 * @see https://tailwindcss.com/docs/break-inside
                 */
                'break-inside' => [['break-inside' => ['auto', 'avoid', 'avoid-page', 'avoid-column']]],
                /**
                 * Box Decoration Break
                 * @see https://tailwindcss.com/docs/box-decoration-break
                 */
                'box-decoration' => [['box-decoration' => ['slice', 'clone']]],
                /**
                 * Box Sizing
                 * @see https://tailwindcss.com/docs/box-sizing
                 */
                'box' => [['box' => ['border', 'content']]],
                /**
                 * Display
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
                 * @see https://tailwindcss.com/docs/float
                 */
                'float' => [['float' => ['right', 'left', 'none']]],
                /**
                 * Clear
                 * @see https://tailwindcss.com/docs/clear
                 */
                'clear' => [['clear' => ['left', 'right', 'both', 'none']]],
                /**
                 * Isolation
                 * @see https://tailwindcss.com/docs/isolation
                 */
                'isolation' => ['isolate', 'isolation-auto'],
                /**
                 * Object Fit
                 * @see https://tailwindcss.com/docs/object-fit
                 */
                'object-fit' => [['object' => ['contain', 'cover', 'fill', 'none', 'scale-down']]],
                /**
                 * Object Position
                 * @see https://tailwindcss.com/docs/object-position
                 */
                'object-position' => [['object' => [...self::getPositions(), self::isArbitraryValue(...)]]],
                /**
                 * Overflow
                 * @see https://tailwindcss.com/docs/overflow
                 */
                'overflow' => [['overflow' => self::getOverflow()]],
                /**
                 * Overflow X
                 * @see https://tailwindcss.com/docs/overflow
                 */
                'overflow-x' => [['overflow-x' => self::getOverflow()]],
                /**
                 * Overflow Y
                 * @see https://tailwindcss.com/docs/overflow
                 */
                'overflow-y' => [['overflow-y' => self::getOverflow()]],
                /**
                 * Overscroll Behavior
                 * @see https://tailwindcss.com/docs/overscroll-behavior
                 */
                'overscroll' => [['overscroll' => self::getOverscroll()]],
                /**
                 * Overscroll Behavior X
                 * @see https://tailwindcss.com/docs/overscroll-behavior
                 */
                'overscroll-x' => [['overscroll-x' => self::getOverscroll()]],
                /**
                 * Overscroll Behavior Y
                 * @see https://tailwindcss.com/docs/overscroll-behavior
                 */
                'overscroll-y' => [['overscroll-y' => self::getOverscroll()]],
                /**
                 * Position
                 * @see https://tailwindcss.com/docs/position
                 */
                'position' => ['static', 'fixed', 'absolute', 'relative', 'sticky'],
                /**
                 * Top / Right / Bottom / Left
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'inset' => [['inset' => [$inset]]],
                /**
                 * Right / Left
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'inset-x' => [['inset-x' => [$inset]]],
                /**
                 * Top / Bottom
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'inset-y' => [['inset-y' => [$inset]]],
                /**
                 * Start
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'start' => [['start' => [$inset]]],
                /**
                 * End
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'end' => [['end' => [$inset]]],
                /**
                 * Top
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'top' => [['top' => [$inset]]],
                /**
                 * Right
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'right' => [['right' => [$inset]]],
                /**
                 * Bottom
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'bottom' => [['bottom' => [$inset]]],
                /**
                 * Left
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'left' => [['left' => [$inset]]],
                /**
                 * Visibility
                 * @see https://tailwindcss.com/docs/visibility
                 */
                'visibility' => ['visible', 'invisible', 'collapse'],
                /**
                 * Z-Index
                 * @see https://tailwindcss.com/docs/z-index
                 */
                'z' => [['z' => ['auto', self::isInteger(...)]]],
                // Flexbox and Grid
                /**
                 * Flex Basis
                 * @see https://tailwindcss.com/docs/flex-basis
                 */
                'basis' => [['basis' => [$spacing]]],
                /**
                 * Flex Direction
                 * @see https://tailwindcss.com/docs/flex-direction
                 */
                'flex-direction' => [['flex' => ['row', 'row-reverse', 'col', 'col-reverse']]],
                /**
                 * Flex Wrap
                 * @see https://tailwindcss.com/docs/flex-wrap
                 */
                'flex-wrap' => [['flex' => ['wrap', 'wrap-reverse', 'nowrap']]],
                /**
                 * Flex
                 * @see https://tailwindcss.com/docs/flex
                 */
                'flex' => [['flex' => ['1', 'auto', 'initial', 'none', self::isArbitraryValue(...)]]],
                /**
                 * Flex Grow
                 * @see https://tailwindcss.com/docs/flex-grow
                 */
                'grow' => [['grow' => self::getZeroAndEmpty()]],
                /**
                 * Flex Shrink
                 * @see https://tailwindcss.com/docs/flex-shrink
                 */
                'shrink' => [['shrink' => self::getZeroAndEmpty()]],
                /**
                 * Order
                 * @see https://tailwindcss.com/docs/order
                 */
                'order' => [['order' => ['first', 'last', 'none', self::isInteger(...)]]],
                /**
                 * Grid Template Columns
                 * @see https://tailwindcss.com/docs/grid-template-columns
                 */
                'grid-cols' => [['grid-cols' => [self::isAny(...)]]],
                /**
                 * Grid Column Start / End
                 * @see https://tailwindcss.com/docs/grid-column
                 */
                'col-start-end' => [['col' => ['auto', ['span' => [self::isInteger(...)]], self::isArbitraryValue(...)]]],
                /**
                 * Grid Column Start
                 * @see https://tailwindcss.com/docs/grid-column
                 */
                'col-start' => [['col-start' => self::getNumberWithAutoAndArbitrary()]],
                /**
                 * Grid Column End
                 * @see https://tailwindcss.com/docs/grid-column
                 */
                'col-end' => [['col-end' => self::getNumberWithAutoAndArbitrary()]],
                /**
                 * Grid Template Rows
                 * @see https://tailwindcss.com/docs/grid-template-rows
                 */
                'grid-rows' => [['grid-rows' => [self::isAny(...)]]],
                /**
                 * Grid Row Start / End
                 * @see https://tailwindcss.com/docs/grid-row
                 */
                'row-start-end' => [['row' => ['auto', ['span' => [self::isInteger(...)]], self::isArbitraryValue(...)]]],
                /**
                 * Grid Row Start
                 * @see https://tailwindcss.com/docs/grid-row
                 */
                'row-start' => [['row-start' => self::getNumberWithAutoAndArbitrary()]],
                /**
                 * Grid Row End
                 * @see https://tailwindcss.com/docs/grid-row
                 */
                'row-end' => [['row-end' => self::getNumberWithAutoAndArbitrary()]],
                /**
                 * Grid Auto Flow
                 * @see https://tailwindcss.com/docs/grid-auto-flow
                 */
                'grid-flow' => [['grid-flow' => ['row', 'col', 'dense', 'row-dense', 'col-dense']]],
                /**
                 * Grid Auto Columns
                 * @see https://tailwindcss.com/docs/grid-auto-columns
                 */
                'auto-cols' => [['auto-cols' => ['auto', 'min', 'max', 'fr', self::isArbitraryValue(...)]]],
                /**
                 * Grid Auto Rows
                 * @see https://tailwindcss.com/docs/grid-auto-rows
                 */
                'auto-rows' => [['auto-rows' => ['auto', 'min', 'max', 'fr', self::isArbitraryValue(...)]]],
                /**
                 * Gap
                 * @see https://tailwindcss.com/docs/gap
                 */
                'gap' => [['gap' => [$gap]]],
                /**
                 * Gap X
                 * @see https://tailwindcss.com/docs/gap
                 */
                'gap-x' => [['gap-x' => [$gap]]],
                /**
                 * Gap Y
                 * @see https://tailwindcss.com/docs/gap
                 */
                'gap-y' => [['gap-y' => [$gap]]],
                /**
                 * Justify Content
                 * @see https://tailwindcss.com/docs/justify-content
                 */
                'justify-content' => [['justify' => ['normal', ...self::getAlign()]]],
                /**
                 * Justify Items
                 * @see https://tailwindcss.com/docs/justify-items
                 */
                'justify-items' => [['justify-items' => ['start', 'end', 'center', 'stretch']]],
                /**
                 * Justify Self
                 * @see https://tailwindcss.com/docs/justify-self
                 */
                'justify-self' => [['justify-self' => ['auto', 'start', 'end', 'center', 'stretch']]],
                /**
                 * Align Content
                 * @see https://tailwindcss.com/docs/align-content
                 */
                'align-content' => [['content' => ['normal', ...self::getAlign(), 'baseline']]],
                /**
                 * Align Items
                 * @see https://tailwindcss.com/docs/align-items
                 */
                'align-items' => [['items' => ['start', 'end', 'center', 'baseline', 'stretch']]],
                /**
                 * Align Self
                 * @see https://tailwindcss.com/docs/align-self
                 */
                'align-self' => [['self' => ['auto', 'start', 'end', 'center', 'stretch', 'baseline']]],
                /**
                 * Place Content
                 * @see https://tailwindcss.com/docs/place-content
                 */
                'place-content' => [['place-content' => [...self::getAlign(), 'baseline']]],
                /**
                 * Place Items
                 * @see https://tailwindcss.com/docs/place-items
                 */
                'place-items' => [['place-items' => ['start', 'end', 'center', 'baseline', 'stretch']]],
                /**
                 * Place Self
                 * @see https://tailwindcss.com/docs/place-self
                 */
                'place-self' => [['place-self' => ['auto', 'start', 'end', 'center', 'stretch']]],
                // Spacing
                /**
                 * Padding
                 * @see https://tailwindcss.com/docs/padding
                 */
                'p' => [['p' => [$padding]]],
                /**
                 * Padding X
                 * @see https://tailwindcss.com/docs/padding
                 */
                'px' => [['px' => [$padding]]],
                /**
                 * Padding Y
                 * @see https://tailwindcss.com/docs/padding
                 */
                'py' => [['py' => [$padding]]],
                /**
                 * Padding Start
                 * @see https://tailwindcss.com/docs/padding
                 */
                'ps' => [['ps' => [$padding]]],
                /**
                 * Padding End
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pe' => [['pe' => [$padding]]],
                /**
                 * Padding Top
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pt' => [['pt' => [$padding]]],
                /**
                 * Padding Right
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pr' => [['pr' => [$padding]]],
                /**
                 * Padding Bottom
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pb' => [['pb' => [$padding]]],
                /**
                 * Padding Left
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pl' => [['pl' => [$padding]]],
                /**
                 * Margin
                 * @see https://tailwindcss.com/docs/margin
                 */
                'm' => [['m' => [$margin]]],
                /**
                 * Margin X
                 * @see https://tailwindcss.com/docs/margin
                 */
                'mx' => [['mx' => [$margin]]],
                /**
                 * Margin Y
                 * @see https://tailwindcss.com/docs/margin
                 */
                'my' => [['my' => [$margin]]],
                /**
                 * Margin Start
                 * @see https://tailwindcss.com/docs/margin
                 */
                'ms' => [['ms' => [$margin]]],
                /**
                 * Margin End
                 * @see https://tailwindcss.com/docs/margin
                 */
                'me' => [['me' => [$margin]]],
                /**
                 * Margin Top
                 * @see https://tailwindcss.com/docs/margin
                 */
                'mt' => [['mt' => [$margin]]],
                /**
                 * Margin Right
                 * @see https://tailwindcss.com/docs/margin
                 */
                'mr' => [['mr' => [$margin]]],
                /**
                 * Margin Bottom
                 * @see https://tailwindcss.com/docs/margin
                 */
                'mb' => [['mb' => [$margin]]],
                /**
                 * Margin Left
                 * @see https://tailwindcss.com/docs/margin
                 */
                'ml' => [['ml' => [$margin]]],
                /**
                 * Space Between X
                 * @see https://tailwindcss.com/docs/space
                 */
                'space-x' => [['space-x' => [$space]]],
                /**
                 * Space Between X Reverse
                 * @see https://tailwindcss.com/docs/space
                 */
                'space-x-reverse' => ['space-x-reverse'],
                /**
                 * Space Between Y
                 * @see https://tailwindcss.com/docs/space
                 */
                'space-y' => [['space-y' => [$space]]],
                /**
                 * Space Between Y Reverse
                 * @see https://tailwindcss.com/docs/space
                 */
                'space-y-reverse' => ['space-y-reverse'],
                // Sizing
                /**
                 * Width
                 * @see https://tailwindcss.com/docs/width
                 */
                'w' => [['w' => ['auto', 'min', 'max', 'fit', $spacing]]],
                /**
                 * Min-Width
                 * @see https://tailwindcss.com/docs/min-width
                 */
                'min-w' => [['min-w' => ['min', 'max', 'fit', self::isLength(...)]]],
                /**
                 * Max-Width
                 * @see https://tailwindcss.com/docs/max-width
                 */
                'max-w' => [
                    [
                        'max-w' => [
                            '0',
                            'none',
                            'full',
                            'min',
                            'max',
                            'fit',
                            'prose',
                            ['screen' => [self::isTshirtSize(...)]],
                            self::isTshirtSize(...),
                            self::isArbitraryLength(...),
                        ],
                    ],
                ],
                /**
                 * Height
                 * @see https://tailwindcss.com/docs/height
                 */
                'h' => [['h' => [$spacing, 'auto', 'min', 'max', 'fit']]],
                /**
                 * Min-Height
                 * @see https://tailwindcss.com/docs/min-height
                 */
                'min-h' => [['min-h' => ['min', 'max', 'fit', self::isLength(...)]]],
                /**
                 * Max-Height
                 * @see https://tailwindcss.com/docs/max-height
                 */
                'max-h' => [['max-h' => [$spacing, 'min', 'max', 'fit']]],
                // Typography
                /**
                 * Font Size
                 * @see https://tailwindcss.com/docs/font-size
                 */
                'font-size' => [['text' => ['base', self::isTshirtSize(...), self::isArbitraryLength(...)]]],
                /**
                 * Font Smoothing
                 * @see https://tailwindcss.com/docs/font-smoothing
                 */
                'font-smoothing' => ['antialiased', 'subpixel-antialiased'],
                /**
                 * Font Style
                 * @see https://tailwindcss.com/docs/font-style
                 */
                'font-style' => ['italic', 'not-italic'],
                /**
                 * Font Weight
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
                            self::isArbitraryNumber(...),
                        ],
                    ],
                ],
                /**
                 * Font Family
                 * @see https://tailwindcss.com/docs/font-family
                 */
                'font-family' => [['font' => [self::isAny(...)]]],
                /**
                 * Font Variant Numeric
                 * @see https://tailwindcss.com/docs/font-variant-numeric
                 */
                'fvn-normal' => ['normal-nums'],
                /**
                 * Font Variant Numeric
                 * @see https://tailwindcss.com/docs/font-variant-numeric
                 */
                'fvn-ordinal' => ['ordinal'],
                /**
                 * Font Variant Numeric
                 * @see https://tailwindcss.com/docs/font-variant-numeric
                 */
                'fvn-slashed-zero' => ['slashed-zero'],
                /**
                 * Font Variant Numeric
                 * @see https://tailwindcss.com/docs/font-variant-numeric
                 */
                'fvn-figure' => ['lining-nums', 'oldstyle-nums'],
                /**
                 * Font Variant Numeric
                 * @see https://tailwindcss.com/docs/font-variant-numeric
                 */
                'fvn-spacing' => ['proportional-nums', 'tabular-nums'],
                /**
                 * Font Variant Numeric
                 * @see https://tailwindcss.com/docs/font-variant-numeric
                 */
                'fvn-fraction' => ['diagonal-fractions', 'stacked-fractons'],
                /**
                 * Letter Spacing
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
                            self::isArbitraryLength(...),
                        ],
                    ],
                ],
                /**
                 * Line Clamp
                 * @see https://tailwindcss.com/docs/line-clamp
                 */
                'line-clamp' => [['line-clamp' => ['none', self::isNumber(...), self::isArbitraryNumber(...)]]],
                /**
                 * Line Height
                 * @see https://tailwindcss.com/docs/line-height
                 */
                'leading' => [
                    ['leading' => ['none', 'tight', 'snug', 'normal', 'relaxed', 'loose', self::isLength(...)]],
                ],
                /**
                 * List Style Image
                 * @see https://tailwindcss.com/docs/list-style-image
                 */
                'list-image' => [['list-image' => ['none', self::isArbitraryValue(...)]]],
                /**
                 * List Style Type
                 * @see https://tailwindcss.com/docs/list-style-type
                 */
                'list-style-type' => [['list' => ['none', 'disc', 'decimal', self::isArbitraryValue(...)]]],
                /**
                 * List Style Position
                 * @see https://tailwindcss.com/docs/list-style-position
                 */
                'list-style-position' => [['list' => ['inside', 'outside']]],
                /**
                 * Placeholder Color
                 * @deprecated since Tailwind CSS v3.0.0
                 * @see https://tailwindcss.com/docs/placeholder-color
                 */
                'placeholder-color' => [['placeholder' => [$colors]]],
                /**
                 * Placeholder Opacity
                 * @see https://tailwindcss.com/docs/placeholder-opacity
                 */
                'placeholder-opacity' => [['placeholder-opacity' => [$opacity]]],
                /**
                 * Text Alignment
                 * @see https://tailwindcss.com/docs/text-align
                 */
                'text-alignment' => [['text' => ['left', 'center', 'right', 'justify', 'start', 'end']]],
                /**
                 * Text Color
                 * @see https://tailwindcss.com/docs/text-color
                 */
                'text-color' => [['text' => [$colors]]],
                /**
                 * Text Opacity
                 * @see https://tailwindcss.com/docs/text-opacity
                 */
                'text-opacity' => [['text-opacity' => [$opacity]]],
                /**
                 * Text Decoration
                 * @see https://tailwindcss.com/docs/text-decoration
                 */
                'text-decoration' => ['underline', 'overline', 'line-through', 'no-underline'],
                /**
                 * Text Decoration Style
                 * @see https://tailwindcss.com/docs/text-decoration-style
                 */
                'text-decoration-style' => [['decoration' => [...self::getLineStyles(), 'wavy']]],
                /**
                 * Text Decoration Thickness
                 * @see https://tailwindcss.com/docs/text-decoration-thickness
                 */
                'text-decoration-thickness' => [['decoration' => ['auto', 'from-font', self::isLength(...)]]],
                /**
                 * Text Underline Offset
                 * @see https://tailwindcss.com/docs/text-underline-offset
                 */
                'underline-offset' => [['underline-offset' => ['auto', self::isLength(...)]]],
                /**
                 * Text Decoration Color
                 * @see https://tailwindcss.com/docs/text-decoration-color
                 */
                'text-decoration-color' => [['decoration' => [$colors]]],
                /**
                 * Text Transform
                 * @see https://tailwindcss.com/docs/text-transform
                 */
                'text-transform' => ['uppercase', 'lowercase', 'capitalize', 'normal-case'],
                /**
                 * Text Overflow
                 * @see https://tailwindcss.com/docs/text-overflow
                 */
                'text-overflow' => ['truncate', 'text-ellipsis', 'text-clip'],
                /**
                 * Text Indent
                 * @see https://tailwindcss.com/docs/text-indent
                 */
                'indent' => [['indent' => [$spacing]]],
                /**
                 * Vertical Alignment
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
                            self::isArbitraryLength(...),
                        ],
                    ],
                ],
                /**
                 * Whitespace
                 * @see https://tailwindcss.com/docs/whitespace
                 */
                'whitespace' => [
                    ['whitespace' => ['normal', 'nowrap', 'pre', 'pre-line', 'pre-wrap', 'break-spaces']],
                ],
                /**
                 * Word Break
                 * @see https://tailwindcss.com/docs/word-break
                 */
                'break' => [['break' => ['normal', 'words', 'all', 'keep']]],
                /**
                 * Hyphens
                 * @see https://tailwindcss.com/docs/hyphens
                 */
                'hyphens' => [['hyphens' => ['none', 'manual', 'auto']]],
                /**
                 * Content
                 * @see https://tailwindcss.com/docs/content
                 */
                'content' => [['content' => ['none', self::isArbitraryValue(...)]]],
                // Backgrounds
                /**
                 * Background Attachment
                 * @see https://tailwindcss.com/docs/background-attachment
                 */
                'bg-attachment' => [['bg' => ['fixed', 'local', 'scroll']]],
                /**
                 * Background Clip
                 * @see https://tailwindcss.com/docs/background-clip
                 */
                'bg-clip' => [['bg-clip' => ['border', 'padding', 'content', 'text']]],
                /**
                 * Background Opacity
                 * @deprecated since Tailwind CSS v3.0.0
                 * @see https://tailwindcss.com/docs/background-opacity
                 */
                'bg-opacity' => [['bg-opacity' => [$opacity]]],
                /**
                 * Background Origin
                 * @see https://tailwindcss.com/docs/background-origin
                 */
                'bg-origin' => [['bg-origin' => ['border', 'padding', 'content']]],
                /**
                 * Background Position
                 * @see https://tailwindcss.com/docs/background-position
                 */
                'bg-position' => [['bg' => [...self::getPositions(), self::isArbitraryPosition(...)]]],
                /**
                 * Background Repeat
                 * @see https://tailwindcss.com/docs/background-repeat
                 */
                'bg-repeat' => [['bg' => ['no-repeat', ['repeat' => ['', 'x', 'y', 'round', 'space']]]]],
                /**
                 * Background Size
                 * @see https://tailwindcss.com/docs/background-size
                 */
                'bg-size' => [['bg' => ['auto', 'cover', 'contain', self::isArbitrarySize(...)]]],
                /**
                 * Background Image
                 * @see https://tailwindcss.com/docs/background-image
                 */
                'bg-image' => [
                    [
                        'bg' => [
                            'none',
                            ['gradient-to' => ['t', 'tr', 'r', 'br', 'b', 'bl', 'l', 'tl']],
                            self::isArbitraryUrl(...),
                        ],
                    ],
                ],
                /**
                 * Background Color
                 * @see https://tailwindcss.com/docs/background-color
                 */
                'bg-color' => [['bg' => [$colors]]],
                /**
                 * Gradient Color Stops From Position
                 * @see https://tailwindcss.com/docs/gradient-color-stops
                 */
                'gradient-from-pos' => [['from' => [$gradientColorStopPositions]]],
                /**
                 * Gradient Color Stops Via Position
                 * @see https://tailwindcss.com/docs/gradient-color-stops
                 */
                'gradient-via-pos' => [['via' => [$gradientColorStopPositions]]],
                /**
                 * Gradient Color Stops To Position
                 * @see https://tailwindcss.com/docs/gradient-color-stops
                 */
                'gradient-to-pos' => [['to' => [$gradientColorStopPositions]]],
                /**
                 * Gradient Color Stops From
                 * @see https://tailwindcss.com/docs/gradient-color-stops
                 */
                'gradient-from' => [['from' => [$gradientColorStops]]],
                /**
                 * Gradient Color Stops Via
                 * @see https://tailwindcss.com/docs/gradient-color-stops
                 */
                'gradient-via' => [['via' => [$gradientColorStops]]],
                /**
                 * Gradient Color Stops To
                 * @see https://tailwindcss.com/docs/gradient-color-stops
                 */
                'gradient-to' => [['to' => [$gradientColorStops]]],
                // Borders
                /**
                 * Border Radius
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded' => [['rounded' => [$borderRadius]]],
                /**
                 * Border Radius Start
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-s' => [['rounded-s' => [$borderRadius]]],
                /**
                 * Border Radius End
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-e' => [['rounded-e' => [$borderRadius]]],
                /**
                 * Border Radius Top
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-t' => [['rounded-t' => [$borderRadius]]],
                /**
                 * Border Radius Right
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-r' => [['rounded-r' => [$borderRadius]]],
                /**
                 * Border Radius Bottom
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-b' => [['rounded-b' => [$borderRadius]]],
                /**
                 * Border Radius Left
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-l' => [['rounded-l' => [$borderRadius]]],
                /**
                 * Border Radius Start Start
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-ss' => [['rounded-ss' => [$borderRadius]]],
                /**
                 * Border Radius Start End
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-se' => [['rounded-se' => [$borderRadius]]],
                /**
                 * Border Radius End End
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-ee' => [['rounded-ee' => [$borderRadius]]],
                /**
                 * Border Radius End Start
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-es' => [['rounded-es' => [$borderRadius]]],
                /**
                 * Border Radius Top Left
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-tl' => [['rounded-tl' => [$borderRadius]]],
                /**
                 * Border Radius Top Right
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-tr' => [['rounded-tr' => [$borderRadius]]],
                /**
                 * Border Radius Bottom Right
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-br' => [['rounded-br' => [$borderRadius]]],
                /**
                 * Border Radius Bottom Left
                 * @see https://tailwindcss.com/docs/border-radius
                 */
                'rounded-bl' => [['rounded-bl' => [$borderRadius]]],
                /**
                 * Border Width
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w' => [['border' => [$borderWidth]]],
                /**
                 * Border Width X
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-x' => [['border-x' => [$borderWidth]]],
                /**
                 * Border Width Y
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-y' => [['border-y' => [$borderWidth]]],
                /**
                 * Border Width Start
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-s' => [['border-s' => [$borderWidth]]],
                /**
                 * Border Width End
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-e' => [['border-e' => [$borderWidth]]],
                /**
                 * Border Width Top
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-t' => [['border-t' => [$borderWidth]]],
                /**
                 * Border Width Right
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-r' => [['border-r' => [$borderWidth]]],
                /**
                 * Border Width Bottom
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-b' => [['border-b' => [$borderWidth]]],
                /**
                 * Border Width Left
                 * @see https://tailwindcss.com/docs/border-width
                 */
                'border-w-l' => [['border-l' => [$borderWidth]]],
                /**
                 * Border Opacity
                 * @see https://tailwindcss.com/docs/border-opacity
                 */
                'border-opacity' => [['border-opacity' => [$opacity]]],
                /**
                 * Border Style
                 * @see https://tailwindcss.com/docs/border-style
                 */
                'border-style' => [['border' => [...self::getLineStyles(), 'hidden']]],
                /**
                 * Divide Width X
                 * @see https://tailwindcss.com/docs/divide-width
                 */
                'divide-x' => [['divide-x' => [$borderWidth]]],
                /**
                 * Divide Width X Reverse
                 * @see https://tailwindcss.com/docs/divide-width
                 */
                'divide-x-reverse' => ['divide-x-reverse'],
                /**
                 * Divide Width Y
                 * @see https://tailwindcss.com/docs/divide-width
                 */
                'divide-y' => [['divide-y' => [$borderWidth]]],
                /**
                 * Divide Width Y Reverse
                 * @see https://tailwindcss.com/docs/divide-width
                 */
                'divide-y-reverse' => ['divide-y-reverse'],
                /**
                 * Divide Opacity
                 * @see https://tailwindcss.com/docs/divide-opacity
                 */
                'divide-opacity' => [['divide-opacity' => [$opacity]]],
                /**
                 * Divide Style
                 * @see https://tailwindcss.com/docs/divide-style
                 */
                'divide-style' => [['divide' => self::getLineStyles()]],
                /**
                 * Border Color
                 * @see https://tailwindcss.com/docs/border-color
                 */
                'border-color' => [['border' => [$borderColor]]],
                /**
                 * Border Color X
                 * @see https://tailwindcss.com/docs/border-color
                 */
                'border-color-x' => [['border-x' => [$borderColor]]],
                /**
                 * Border Color Y
                 * @see https://tailwindcss.com/docs/border-color
                 */
                'border-color-y' => [['border-y' => [$borderColor]]],
                /**
                 * Border Color Top
                 * @see https://tailwindcss.com/docs/border-color
                 */
                'border-color-t' => [['border-t' => [$borderColor]]],
                /**
                 * Border Color Right
                 * @see https://tailwindcss.com/docs/border-color
                 */
                'border-color-r' => [['border-r' => [$borderColor]]],
                /**
                 * Border Color Bottom
                 * @see https://tailwindcss.com/docs/border-color
                 */
                'border-color-b' => [['border-b' => [$borderColor]]],
                /**
                 * Border Color Left
                 * @see https://tailwindcss.com/docs/border-color
                 */
                'border-color-l' => [['border-l' => [$borderColor]]],
                /**
                 * Divide Color
                 * @see https://tailwindcss.com/docs/divide-color
                 */
                'divide-color' => [['divide' => [$borderColor]]],
                /**
                 * Outline Style
                 * @see https://tailwindcss.com/docs/outline-style
                 */
                'outline-style' => [['outline' => ['', ...self::getLineStyles()]]],
                /**
                 * Outline Offset
                 * @see https://tailwindcss.com/docs/outline-offset
                 */
                'outline-offset' => [['outline-offset' => [self::isLength(...)]]],
                /**
                 * Outline Width
                 * @see https://tailwindcss.com/docs/outline-width
                 */
                'outline-w' => [['outline' => [self::isLength(...)]]],
                /**
                 * Outline Color
                 * @see https://tailwindcss.com/docs/outline-color
                 */
                'outline-color' => [['outline' => [$colors]]],
                /**
                 * Ring Width
                 * @see https://tailwindcss.com/docs/ring-width
                 */
                'ring-w' => [['ring' => self::getLengthWithEmpty()]],
                /**
                 * Ring Width Inset
                 * @see https://tailwindcss.com/docs/ring-width
                 */
                'ring-w-inset' => ['ring-inset'],
                /**
                 * Ring Color
                 * @see https://tailwindcss.com/docs/ring-color
                 */
                'ring-color' => [['ring' => [$colors]]],
                /**
                 * Ring Opacity
                 * @see https://tailwindcss.com/docs/ring-opacity
                 */
                'ring-opacity' => [['ring-opacity' => [$opacity]]],
                /**
                 * Ring Offset Width
                 * @see https://tailwindcss.com/docs/ring-offset-width
                 */
                'ring-offset-w' => [['ring-offset' => [self::isLength(...)]]],
                /**
                 * Ring Offset Color
                 * @see https://tailwindcss.com/docs/ring-offset-color
                 */
                'ring-offset-color' => [['ring-offset' => [$colors]]],
                // Effects
                /**
                 * Box Shadow
                 * @see https://tailwindcss.com/docs/box-shadow
                 */
                'shadow' => [['shadow' => ['', 'inner', 'none', self::isTshirtSize(...), self::isArbitraryShadow(...)]]],
                /**
                 * Box Shadow Color
                 * @see https://tailwindcss.com/docs/box-shadow-color
                 */
                'shadow-color' => [['shadow' => [self::isAny(...)]]],
                /**
                 * Opacity
                 * @see https://tailwindcss.com/docs/opacity
                 */
                'opacity' => [['opacity' => [$opacity]]],
                /**
                 * Mix Blend Mode
                 * @see https://tailwindcss.com/docs/mix-blend-mode
                 */
                'mix-blend' => [['mix-blend' => self::getBlendModes()]],
                /**
                 * Background Blend Mode
                 * @see https://tailwindcss.com/docs/background-blend-mode
                 */
                'bg-blend' => [['bg-blend' => self::getBlendModes()]],
                // Filters
                /**
                 * Filter
                 * @deprecated since Tailwind CSS v3.0.0
                 * @see https://tailwindcss.com/docs/filter
                 */
                'filter' => [['filter' => ['', 'none']]],
                /**
                 * Blur
                 * @see https://tailwindcss.com/docs/blur
                 */
                'blur' => [['blur' => [$blur]]],
                /**
                 * Brightness
                 * @see https://tailwindcss.com/docs/brightness
                 */
                'brightness' => [['brightness' => [$brightness]]],
                /**
                 * Contrast
                 * @see https://tailwindcss.com/docs/contrast
                 */
                'contrast' => [['contrast' => [$contrast]]],
                /**
                 * Drop Shadow
                 * @see https://tailwindcss.com/docs/drop-shadow
                 */
                'drop-shadow' => [['drop-shadow' => ['', 'none', self::isTshirtSize(...), self::isArbitraryValue(...)]]],
                /**
                 * Grayscale
                 * @see https://tailwindcss.com/docs/grayscale
                 */
                'grayscale' => [['grayscale' => [$grayscale]]],
                /**
                 * Hue Rotate
                 * @see https://tailwindcss.com/docs/hue-rotate
                 */
                'hue-rotate' => [['hue-rotate' => [$hueRotate]]],
                /**
                 * Invert
                 * @see https://tailwindcss.com/docs/invert
                 */
                'invert' => [['invert' => [$invert]]],
                /**
                 * Saturate
                 * @see https://tailwindcss.com/docs/saturate
                 */
                'saturate' => [['saturate' => [$saturate]]],
                /**
                 * Sepia
                 * @see https://tailwindcss.com/docs/sepia
                 */
                'sepia' => [['sepia' => [$sepia]]],
                /**
                 * Backdrop Filter
                 * @deprecated since Tailwind CSS v3.0.0
                 * @see https://tailwindcss.com/docs/backdrop-filter
                 */
                'backdrop-filter' => [['backdrop-filter' => ['', 'none']]],
                /**
                 * Backdrop Blur
                 * @see https://tailwindcss.com/docs/backdrop-blur
                 */
                'backdrop-blur' => [['backdrop-blur' => [$blur]]],
                /**
                 * Backdrop Brightness
                 * @see https://tailwindcss.com/docs/backdrop-brightness
                 */
                'backdrop-brightness' => [['backdrop-brightness' => [$brightness]]],
                /**
                 * Backdrop Contrast
                 * @see https://tailwindcss.com/docs/backdrop-contrast
                 */
                'backdrop-contrast' => [['backdrop-contrast' => [$contrast]]],
                /**
                 * Backdrop Grayscale
                 * @see https://tailwindcss.com/docs/backdrop-grayscale
                 */
                'backdrop-grayscale' => [['backdrop-grayscale' => [$grayscale]]],
                /**
                 * Backdrop Hue Rotate
                 * @see https://tailwindcss.com/docs/backdrop-hue-rotate
                 */
                'backdrop-hue-rotate' => [['backdrop-hue-rotate' => [$hueRotate]]],
                /**
                 * Backdrop Invert
                 * @see https://tailwindcss.com/docs/backdrop-invert
                 */
                'backdrop-invert' => [['backdrop-invert' => [$invert]]],
                /**
                 * Backdrop Opacity
                 * @see https://tailwindcss.com/docs/backdrop-opacity
                 */
                'backdrop-opacity' => [['backdrop-opacity' => [$opacity]]],
                /**
                 * Backdrop Saturate
                 * @see https://tailwindcss.com/docs/backdrop-saturate
                 */
                'backdrop-saturate' => [['backdrop-saturate' => [$saturate]]],
                /**
                 * Backdrop Sepia
                 * @see https://tailwindcss.com/docs/backdrop-sepia
                 */
                'backdrop-sepia' => [['backdrop-sepia' => [$sepia]]],
                // Tables
                /**
                 * Border Collapse
                 * @see https://tailwindcss.com/docs/border-collapse
                 */
                'border-collapse' => [['border' => ['collapse', 'separate']]],
                /**
                 * Border Spacing
                 * @see https://tailwindcss.com/docs/border-spacing
                 */
                'border-spacing' => [['border-spacing' => [$borderSpacing]]],
                /**
                 * Border Spacing X
                 * @see https://tailwindcss.com/docs/border-spacing
                 */
                'border-spacing-x' => [['border-spacing-x' => [$borderSpacing]]],
                /**
                 * Border Spacing Y
                 * @see https://tailwindcss.com/docs/border-spacing
                 */
                'border-spacing-y' => [['border-spacing-y' => [$borderSpacing]]],
                /**
                 * Table Layout
                 * @see https://tailwindcss.com/docs/table-layout
                 */
                'table-layout' => [['table' => ['auto', 'fixed']]],
                /**
                 * Caption Side
                 * @see https://tailwindcss.com/docs/caption-side
                 */
                'caption' => [['caption' => ['top', 'bottom']]],
                // Transitions and Animation
                /**
                 * Tranisition Property
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
                            self::isArbitraryValue(...),
                        ],
                    ],
                ],
                /**
                 * Transition Duration
                 * @see https://tailwindcss.com/docs/transition-duration
                 */
                'duration' => [['duration' => self::getNumberAndArbitrary()]],
                /**
                 * Transition Timing Function
                 * @see https://tailwindcss.com/docs/transition-timing-function
                 */
                'ease' => [['ease' => ['linear', 'in', 'out', 'in-out', self::isArbitraryValue(...)]]],
                /**
                 * Transition Delay
                 * @see https://tailwindcss.com/docs/transition-delay
                 */
                'delay' => [['delay' => self::getNumberAndArbitrary()]],
                /**
                 * Animation
                 * @see https://tailwindcss.com/docs/animation
                 */
                'animate' => [['animate' => ['none', 'spin', 'ping', 'pulse', 'bounce', self::isArbitraryValue(...)]]],
                // Transforms
                /**
                 * Transform
                 * @see https://tailwindcss.com/docs/transform
                 */
                'transform' => [['transform' => ['', 'gpu', 'none']]],
                /**
                 * Scale
                 * @see https://tailwindcss.com/docs/scale
                 */
                'scale' => [['scale' => [$scale]]],
                /**
                 * Scale X
                 * @see https://tailwindcss.com/docs/scale
                 */
                'scale-x' => [['scale-x' => [$scale]]],
                /**
                 * Scale Y
                 * @see https://tailwindcss.com/docs/scale
                 */
                'scale-y' => [['scale-y' => [$scale]]],
                /**
                 * Rotate
                 * @see https://tailwindcss.com/docs/rotate
                 */
                'rotate' => [['rotate' => [self::isInteger(...), self::isArbitraryValue(...)]]],
                /**
                 * Translate X
                 * @see https://tailwindcss.com/docs/translate
                 */
                'translate-x' => [['translate-x' => [$translate]]],
                /**
                 * Translate Y
                 * @see https://tailwindcss.com/docs/translate
                 */
                'translate-y' => [['translate-y' => [$translate]]],
                /**
                 * Skew X
                 * @see https://tailwindcss.com/docs/skew
                 */
                'skew-x' => [['skew-x' => [$skew]]],
                /**
                 * Skew Y
                 * @see https://tailwindcss.com/docs/skew
                 */
                'skew-y' => [['skew-y' => [$skew]]],
                /**
                 * Transform Origin
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
                            self::isArbitraryValue(...),
                        ],
                    ],
                ],
                // Interactivity
                /**
                 * Accent Color
                 * @see https://tailwindcss.com/docs/accent-color
                 */
                'accent' => [['accent' => ['auto', $colors]]],
                /**
                 * Appearance
                 * @see https://tailwindcss.com/docs/appearance
                 */
                'appearance' => ['appearance-none'],
                /**
                 * Cursor
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
                            self::isArbitraryValue(...),
                        ],
                    ],
                ],
                /**
                 * Caret Color
                 * @see https://tailwindcss.com/docs/just-in-time-mode#caret-color-utilities
                 */
                'caret-color' => [['caret' => [$colors]]],
                /**
                 * Pointer Events
                 * @see https://tailwindcss.com/docs/pointer-events
                 */
                'pointer-events' => [['pointer-events' => ['none', 'auto']]],
                /**
                 * Resize
                 * @see https://tailwindcss.com/docs/resize
                 */
                'resize' => [['resize' => ['none', 'y', 'x', '']]],
                /**
                 * Scroll Behavior
                 * @see https://tailwindcss.com/docs/scroll-behavior
                 */
                'scroll-behavior' => [['scroll' => ['auto', 'smooth']]],
                /**
                 * Scroll Margin
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-m' => [['scroll-m' => [$spacing]]],
                /**
                 * Scroll Margin X
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-mx' => [['scroll-mx' => [$spacing]]],
                /**
                 * Scroll Margin Y
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-my' => [['scroll-my' => [$spacing]]],
                /**
                 * Scroll Margin Start
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-ms' => [['scroll-ms' => [$spacing]]],
                /**
                 * Scroll Margin End
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-me' => [['scroll-me' => [$spacing]]],
                /**
                 * Scroll Margin Top
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-mt' => [['scroll-mt' => [$spacing]]],
                /**
                 * Scroll Margin Right
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-mr' => [['scroll-mr' => [$spacing]]],
                /**
                 * Scroll Margin Bottom
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-mb' => [['scroll-mb' => [$spacing]]],
                /**
                 * Scroll Margin Left
                 * @see https://tailwindcss.com/docs/scroll-margin
                 */
                'scroll-ml' => [['scroll-ml' => [$spacing]]],
                /**
                 * Scroll Padding
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-p' => [['scroll-p' => [$spacing]]],
                /**
                 * Scroll Padding X
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-px' => [['scroll-px' => [$spacing]]],
                /**
                 * Scroll Padding Y
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-py' => [['scroll-py' => [$spacing]]],
                /**
                 * Scroll Padding Start
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-ps' => [['scroll-ps' => [$spacing]]],
                /**
                 * Scroll Padding End
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-pe' => [['scroll-pe' => [$spacing]]],
                /**
                 * Scroll Padding Top
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-pt' => [['scroll-pt' => [$spacing]]],
                /**
                 * Scroll Padding Right
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-pr' => [['scroll-pr' => [$spacing]]],
                /**
                 * Scroll Padding Bottom
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-pb' => [['scroll-pb' => [$spacing]]],
                /**
                 * Scroll Padding Left
                 * @see https://tailwindcss.com/docs/scroll-padding
                 */
                'scroll-pl' => [['scroll-pl' => [$spacing]]],
                /**
                 * Scroll Snap Align
                 * @see https://tailwindcss.com/docs/scroll-snap-align
                 */
                'snap-align' => [['snap' => ['start', 'end', 'center', 'align-none']]],
                /**
                 * Scroll Snap Stop
                 * @see https://tailwindcss.com/docs/scroll-snap-stop
                 */
                'snap-stop' => [['snap' => ['normal', 'always']]],
                /**
                 * Scroll Snap Type
                 * @see https://tailwindcss.com/docs/scroll-snap-type
                 */
                'snap-type' => [['snap' => ['none', 'x', 'y', 'both']]],
                /**
                 * Scroll Snap Type Strictness
                 * @see https://tailwindcss.com/docs/scroll-snap-type
                 */
                'snap-strictness' => [['snap' => ['mandatory', 'proximity']]],
                /**
                 * Touch Action
                 * @see https://tailwindcss.com/docs/touch-action
                 */
                'touch' => [
                    [
                        'touch' => [
                            'auto',
                            'none',
                            'pinch-zoom',
                            'manipulation',
                            ['pan' => ['x', 'left', 'right', 'y', 'up', 'down']],
                        ],
                    ],
                ],
                /**
                 * User Select
                 * @see https://tailwindcss.com/docs/user-select
                 */
                'select' => [['select' => ['none', 'text', 'all', 'auto']]],
                /**
                 * Will Change
                 * @see https://tailwindcss.com/docs/will-change
                 */
                'will-change' => [
                    ['will-change' => ['auto', 'scroll', 'contents', 'transform', self::isArbitraryValue(...)]],
                ],
                // SVG
                /**
                 * Fill
                 * @see https://tailwindcss.com/docs/fill
                 */
                'fill' => [['fill' => [$colors, 'none']]],
                /**
                 * Stroke Width
                 * @see https://tailwindcss.com/docs/stroke-width
                 */
                'stroke-w' => [['stroke' => [self::isLength(...), self::isArbitraryNumber(...)]]],
                /**
                 * Stroke
                 * @see https://tailwindcss.com/docs/stroke
                 */
                'stroke' => [['stroke' => [$colors, 'none']]],
                // Accessibility
                /**
                 * Screen Readers
                 * @see https://tailwindcss.com/docs/screen-readers
                 */
                'sr' => ['sr-only', 'not-sr-only'],
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
            ],
            'conflictingClassGroupModifiers' => [
                'font-size' => ['leading'],
            ],
        ];
    }

    private static function fromTheme(string $key): ThemeGetter
    {
        return new ThemeGetter($key);
    }

    public static function isLength($value)
    {
        return (
            self::isNumber($value) ||
            self::stringLengths()->contains($value) ||
            Str::isMatch(self::FRACTION_REGEX, $value) ||
            self::isArbitraryLength($value)
        );
    }

    private static function isNumber($value)
    {
        return is_numeric($value);
    }

    private static function stringLengths()
    {
        return collect(['px', 'full', 'screen']);
    }

    public static function isArbitraryLength($value)
    {
        return self::getIsArbitraryValue($value, 'length', self::isLengthOnly(...));
    }

    private static function isLengthOnly($value)
    {
        return Str::isMatch(self::LENGTH_UNIT_REGEX, $value);
    }

    private static function getIsArbitraryValue($value, string $label, $isLengthOnly): bool
    {
        preg_match(self::ARBITRARY_VALUE_REGEX, $value, $result);

        if ($result) {
            if ($result[1]) {
                return $result[1] === $label;
            }

            return $isLengthOnly($result[2] ?? null);
        }

        return false;
    }

    public static function isArbitraryValue(string $value): bool
    {
        return Str::isMatch(self::ARBITRARY_VALUE_REGEX, $value);
    }

    public static function isArbitraryNumber(string $value)
    {
        return self::getIsArbitraryValue($value, 'number', self::isNumber(...));
    }

    public static function isAny(string $value)
    {
        return true;
    }

    public static function isTshirtSize(string $value)
    {
        return Str::isMatch(self::T_SHIRT_UNIT_REGEX, $value);
    }

    private static function getNumber()
    {
        return [self::isNumber(...), self::isArbitraryNumber(...)];
    }

    private static function getLengthWithEmpty()
    {
        return ['', self::isLength(...)];
    }

    private static function getZeroAndEmpty()
    {
        return ['', '0', self::isArbitraryValue(...)];
    }

    private static function getNumberAndArbitrary()
    {
        return [self::isNumber(...), self::isArbitraryValue(...)];
    }

    public static function isPercent(string $value)
    {
        return Str::endsWith($value, '%') && self::isNumber(Str::of($value)->substr(0, -1)->toString());
    }

    private static function getSpacingWithAuto($spacing)
    {
        return ['auto', $spacing];
    }

    private static function getBreaks()
    {
        return ['auto', 'avoid', 'all', 'avoid-page', 'page', 'left', 'right', 'column'];
    }

    private static function getPositions()
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

    private static function getOverflow()
    {
        return ['auto', 'hidden', 'clip', 'visible', 'scroll'];
    }

    private static function getOverscroll()
    {
        return ['auto', 'contain', 'none'];
    }

    public static function isInteger(string $value)
    {
        return self::isIntegerOnly($value) || self::getIsArbitraryValue($value, 'number', self::isIntegerOnly(...));
    }

    private static function isIntegerOnly(string $value)
    {
        return (string)(int)$value === (string)$value;
    }

    private static function getNumberWithAutoAndArbitrary()
    {
        return ['auto', self::isNumber(...), self::isArbitraryValue(...)];
    }

    private static function getAlign()
    {
        return ['start', 'end', 'center', 'between', 'around', 'evenly', 'stretch'];
    }

    private static function getLineStyles()
    {
        return ['solid', 'dashed', 'dotted', 'double', 'none'];
    }

    public static function isArbitraryPosition(string $value)
    {
        return self::getIsArbitraryValue($value, 'position', self::isNever(...));
    }

    public static function isArbitrarySize(string $value)
    {
        return self::getIsArbitraryValue($value, 'size', self::isNever(...));
    }

    public static function isArbitraryUrl(string $value)
    {
        return self::getIsArbitraryValue($value, 'url', self::isUrl(...));
    }

    public static function isArbitraryShadow(string $value)
    {
        return self::getIsArbitraryValue($value, '', self::isShadow(...));
    }

    private static function isNever(string $value)
    {
        return false;
    }

    private static function isUrl(string $value)
    {
        return Str::startsWith($value, 'url(');
    }

    private static function isShadow(string $value)
    {
        return Str::isMatch(self::SHADOW_REGEX, $value);
    }

    private static function getBlendModes()
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
