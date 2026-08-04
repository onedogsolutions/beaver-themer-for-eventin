# Beaver Themer for Eventin — Implementation Plan

## Current correction: clarify checkout engine requirements

### Background
Eventin ships with its own native cart and checkout. WooCommerce and FluentCart are optional integrations that Eventin can use, but neither is required for Eventin to function, and neither is required by this plugin.

### Problem statement
README, STATE, and module docblocks incorrectly frame WooCommerce or FluentCart as part of the required/target stack. This causes confusion in plugin documentation and implies dependencies that do not exist.

### Required changes

1. **README.md**
   - Change Eventin Event Tickets description from "replaces the WooCommerce ticket form" to "Eventin's own ticket / registration form".
   - Change migration framing from "replacing The Events Calendar (+ WooCommerce Event Tickets) with Eventin" to "replacing The Events Calendar + WooCommerce Event Tickets with Eventin".
   - Remove "longer-term target stack is Eventin + FluentCart" language; replace with note that Eventin supports its own cart and optionally WooCommerce/FluentCart.

2. **STATE.md**
   - Update migration context to reflect Eventin's native cart and optional checkout integrations.
   - Update roadmap item about FluentCart verification to be optional, not assumed.

3. **modules/eventin-tickets/eventin-tickets.php**
   - Update class docblock: remove "replacement for the WooCommerce ticket form" wording.

4. **readme.txt**
   - Update Eventin Event Tickets description to remove WooCommerce-specific framing.

5. **Version bump**
   - Bump to 1.1.1 (patch release: documentation/comment correction).

### Verification
- Plugin dependency header remains `Requires Plugins: wp-event-solution` only.
- No runtime behavior changes; only strings and comments updated.
- PHP lint passes on all modified files.
- Staging sanity test confirms field connections still resolve.
