# Global Make a Mark website design capture

Captured 2026-10-18 from https://www.letsmakeamark.org/ using Chromium at
1440 x 1000, reduced motion enabled. These are live implementation values,
not an official brand specification.

Stylesheet:
https://cdn.prod.website-files.com/657e294654838aafd287debc/css/makeamarkguide.webflow.3fbc0b162.css

| CSS variable | Value | Measured homepage background use |
| --- | --- | --- |
| `--ad3f3f` | `#C33F40` | `.home-logos-block`, `.home-skill-block`, `.home-hero-bottom` |
| `--indian-red` | `#D14B4C` | `.home-logo-item` |
| `--antique-white` | `#F5E2CA` | `.home-hero`, `.home-logo-intro`, `.home-benefits` |
| `--antique-white-2` | `#EBD5BB` | `.home-portfolio-item` |

Additional declared values include `--antique-white-3: #EBD8C1` and
`--bisque: #E5CEB1`; these are not the two homepage schedule panels' backgrounds.

Other retrieved declarations and computed styles:

- Computed headline family `Humane vf`; paragraph family `Generalsans`.
  Humane's font declaration has variable weight 100 to 700.
- `.heading-style-xl`: `font-size: 16vw`, `line-height: .9`,
  `font-variation-settings: "wght" 450`; color `--indian-red`.
- `.noise`: repeating 15rem square image, `opacity: .06`.
- `.home-hero-image` variants: rotations -10deg, 8.5deg and 10deg.
- `.image-overlay`:
  `backdrop-filter: sepia(70%) brightness(95%) grayscale(90%)`;
  `background-color: rgba(121, 26, 27, .2)`.
- `.button.is-icon`: inline text/icon arrangement. Arrow assets include
  upright red and light variants.

Owner feedback during this review:

> Also color-wise, there's more nuance in the international site. Two shades of red. Two shades of cream. Also the black and white treatment of the photos helps to break loose from the cream.
