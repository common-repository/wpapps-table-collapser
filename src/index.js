import { registerBlockType } from '@wordpress/blocks';
import { createBlock } from '@wordpress/blocks';
import { InspectorControls, InnerBlocks } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import './style.css';

// Allowed blocks for the inner blocks
const ALLOWED_BLOCKS = ['core/table'];

registerBlockType('wpapps-tc/table-collapser', {
    title: __('Table Collapser', 'wpapps-table-collapser'),
    icon: 'editor-table',
    category: 'common',
    attributes: {
        dataTitle: {
            type: 'string',
            default: 'Table',
        },
    },
    edit: ({ attributes, setAttributes }) => {
        const { dataTitle } = attributes;

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Settings', 'wpapps-table-collapser')}>
                        <TextControl
                            label={__('Data Title', 'wpapps-table-collapser')}
                            value={dataTitle}
                            onChange={(value) => setAttributes({ dataTitle: value })}
                        />
                    </PanelBody>
                </InspectorControls>
                <div className="collapsible-table-wrapper">
                    <InnerBlocks
                        allowedBlocks={ALLOWED_BLOCKS}
                        template={[['core/table']]}
                        templateLock="all"
                    />
                </div>
            </>
        );
    },
    save: ({ attributes }) => {
        const { dataTitle } = attributes;

        return (
            <div className="collapsible-table-wrapper" data-title={dataTitle}>
                <InnerBlocks.Content />
            </div>
        );
    },
    deprecated: [
        {
            attributes: {
                dataTitle: {
                    type: 'string',
                    default: 'Table',
                },
            },
            save: ({ attributes }) => {
                const { dataTitle } = attributes;

                return (
                    <div className="collapsible-table-wrapper" data-title={dataTitle}>
                        <InnerBlocks.Content />
                    </div>
                );
            },
        },
    ],
});

// Filter to add the 'collapsible-table' class to the inner table element
wp.hooks.addFilter(
    'blocks.getSaveElement',
    'wpapps-tc/table-collapser',
    (element, blockType, attributes) => {
        if (blockType.name === 'core/table') {
            return wp.element.cloneElement(element, {
                className: (element.props.className || '') + ' collapsible-table',
            });
        }
        return element;
    }
);