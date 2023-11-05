import { registerCheckoutBlock } from '@woocommerce/blocks-checkout';

import Block from './_block';
import metadata from './block.json';

registerCheckoutBlock({
    metadata,
    component: Block,
});
