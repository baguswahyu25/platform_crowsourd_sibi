---
name: Precision Crowdsourcing Aesthetic
colors:
  surface: '#faf8ff'
  surface-dim: '#d9d9e5'
  surface-bright: '#faf8ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f3fe'
  surface-container: '#ededf9'
  surface-container-high: '#e7e7f3'
  surface-container-highest: '#e1e2ed'
  on-surface: '#191b23'
  on-surface-variant: '#434655'
  inverse-surface: '#2e3039'
  inverse-on-surface: '#f0f0fb'
  outline: '#737686'
  outline-variant: '#c3c6d7'
  surface-tint: '#0053db'
  primary: '#004ac6'
  on-primary: '#ffffff'
  primary-container: '#2563eb'
  on-primary-container: '#eeefff'
  inverse-primary: '#b4c5ff'
  secondary: '#0058be'
  on-secondary: '#ffffff'
  secondary-container: '#2170e4'
  on-secondary-container: '#fefcff'
  tertiary: '#943700'
  on-tertiary: '#ffffff'
  tertiary-container: '#bc4800'
  on-tertiary-container: '#ffede6'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe1ff'
  primary-fixed-dim: '#b4c5ff'
  on-primary-fixed: '#00174b'
  on-primary-fixed-variant: '#003ea8'
  secondary-fixed: '#d8e2ff'
  secondary-fixed-dim: '#adc6ff'
  on-secondary-fixed: '#001a42'
  on-secondary-fixed-variant: '#004395'
  tertiary-fixed: '#ffdbcd'
  tertiary-fixed-dim: '#ffb596'
  on-tertiary-fixed: '#360f00'
  on-tertiary-fixed-variant: '#7d2d00'
  background: '#faf8ff'
  on-background: '#191b23'
  surface-variant: '#e1e2ed'
typography:
  display-lg:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  title-lg:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.02em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  2xl: 48px
  3xl: 64px
  container-max: 1280px
  gutter: 24px
---

## Brand & Style

The design system prioritizes clarity, efficiency, and a high-fidelity "utility-luxury" feel, tailored for the SIBI Dataset Platform. Drawing inspiration from industry leaders like Linear and Stripe, the aesthetic is rooted in **Modern Minimalism**. It leverages expansive white space, precise geometry, and a systematic approach to depth to reduce cognitive load for users performing complex data tasks.

The emotional response should be one of "trustworthy innovation"—a platform that feels both technically robust and effortlessly usable. Visual interest is generated through subtle micro-interactions, high-quality typography, and a refined sense of layering rather than decorative elements.

## Colors

This design system utilizes a refined palette that balances high-contrast functional text with a soft, expansive background. 

- **Primary & Secondary:** A cohesive blue spectrum used for core actions, focus states, and progress indicators.
- **Surface & Background:** A subtle distinction between the page background (`#F8FAFC`) and component surfaces (`#FFFFFF`) creates natural depth without the need for heavy borders.
- **Semantic Colors:** Success, Warning, and Danger colors are calibrated for high legibility against the light background, ensuring critical status information is immediately recognizable.

## Typography

The typography system is built exclusively on **Inter**, a typeface designed for screens. It employs a tight scale with slightly negative letter spacing on larger headings to achieve that "premium tech" look.

- **Headlines:** Use Semi-Bold (600) weights to provide clear hierarchy without feeling overly aggressive.
- **Body Text:** Standardized at 16px for optimal readability in data-heavy environments.
- **Labels:** Use Medium (500) weights to differentiate metadata and UI controls from standard prose.

## Layout & Spacing

The design system is governed by a strict **8px linear grid**. All dimensions, padding, and margins must be multiples of 8 (or 4 for micro-adjustments).

- **Grid Strategy:** A 12-column fluid grid for desktop with 24px gutters. For data dashboards, a fixed-width sidebar (280px) with a fluid content area is preferred.
- **Margins:** Page-level margins should be 32px on desktop, scaling down to 16px on mobile devices.
- **Stacking:** Vertical spacing between unrelated sections should default to 48px or 64px to maintain the "clean and airy" brand promise.

## Elevation & Depth

Visual hierarchy is established through a combination of **Tonal Layering** and **Soft Ambient Shadows**. This creates a physical sense of "stacking" without the visual clutter of heavy outlines.

- **Level 0 (Base):** The `#F8FAFC` background.
- **Level 1 (Surface):** White cards (`#FFFFFF`) with a very subtle 1px border in `#E2E8F0` and no shadow.
- **Level 2 (Raised):** Interactive elements or hover states. Apply a subtle shadow: `0px 4px 6px -1px rgba(0, 0, 0, 0.05), 0px 2px 4px -2px rgba(0, 0, 0, 0.05)`.
- **Level 3 (Overlay):** Modals and dropdowns. Use a deeper, more diffused shadow: `0px 20px 25px -5px rgba(0, 0, 0, 0.1), 0px 8px 10px -6px rgba(0, 0, 0, 0.1)`.
- **Backdrop:** All overlays should trigger a background blur (`backdrop-filter: blur(8px)`) on the elements behind them to maintain focus.

## Shapes

The shape language is defined by generous, friendly rounding that softens the "technical" nature of a dataset platform.

- **Cards & Containers:** Use a 20px radius for primary containers and dashboard widgets.
- **Interactive Elements:** Buttons use a 14px radius, providing a distinct look that separates them from larger layout blocks.
- **Input Fields:** Standardized at 12px for a modern, approachable feel.
- **Selection States:** Checkboxes and radio buttons should maintain a 4px and "full" (circle) radius respectively.

## Components

### Buttons
- **Primary:** Solid `#2563EB` with white text. 14px radius. Subtle inner top-light highlight for a tactile feel.
- **Secondary:** White surface, `#E2E8F0` border, `#0F172A` text.
- **Sizing:** Height of 40px (Medium) and 48px (Large).

### Cards
- **Style:** Background `#FFFFFF`, 20px radius, 1px `#E2E8F0` border.
- **Interaction:** On hover, transition to Level 2 elevation (Soft Shadow).

### Inputs
- **Style:** 1px `#E2E8F0` border, `#FFFFFF` background, 12px radius. 
- **Focus:** 2px ring of `#3B82F6` with an additional 2px offset.
- **Icons:** Use modern 20px outline icons (2px stroke) in `#64748B`.

### Chips / Tags
- **Style:** 100px (Pill) radius, light tinted backgrounds (e.g., Primary Blue at 10% opacity) with dark colored text.

### Data Tables
- **Header:** Background `#F8FAFC`, uppercase 12px Bold text, 48px height.
- **Rows:** 64px height, subtle bottom border only (`#F1F5F9`). No vertical borders.

### Progress Indicators
- **Style:** Rounded 8px tracks, using the Primary blue or Success green for completion states. Avoid sharp edges on bar ends.