/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { AsyncModeProvider, useSelect } from '@wordpress/data';
import { useRef, createContext, useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import BlockListBlock from './block';
import BlockListAppender from '../block-list-appender';
import useBlockDropZone from '../use-block-drop-zone';
import useInsertionPoint from './insertion-point';
import BlockPopover from './block-popover';
import { store as blockEditorStore } from '../../store';
import { useScrollSelectionIntoView } from '../selection-scroll-into-view';
import { useInnerBlocksProps } from '../inner-blocks';

export const BlockNodes = createContext();
export const SetBlockNodes = createContext();

export default function BlockList( { className } ) {
	const ref = useRef();
	const [ blockNodes, setBlockNodes ] = useState( {} );
	const insertionPoint = useInsertionPoint( ref );
	useScrollSelectionIntoView( ref );
	const innerBlocksProps = useInnerBlocksProps( {
		ref,
		className: classnames( 'is-root-container', className ),
	} );

	return (
		<BlockNodes.Provider value={ blockNodes }>
			{ insertionPoint }
			<BlockPopover />
			<SetBlockNodes.Provider value={ setBlockNodes }>
				<div { ...innerBlocksProps } />
			</SetBlockNodes.Provider>
		</BlockNodes.Provider>
	);
}

function Items( {
	placeholder,
	rootClientId,
	renderAppender,
	__experimentalAppenderTagName,
	wrapperRef,
} ) {
	function selector( select ) {
		const {
			getBlockOrder,
			getBlockListSettings,
			getSelectedBlockClientId,
			getMultiSelectedBlockClientIds,
			hasMultiSelection,
		} = select( blockEditorStore );
		return {
			blockClientIds: getBlockOrder( rootClientId ),
			selectedBlockClientId: getSelectedBlockClientId(),
			multiSelectedBlockClientIds: getMultiSelectedBlockClientIds(),
			orientation: getBlockListSettings( rootClientId )?.orientation,
			hasMultiSelection: hasMultiSelection(),
		};
	}

	const {
		blockClientIds,
		selectedBlockClientId,
		multiSelectedBlockClientIds,
		orientation,
		hasMultiSelection,
	} = useSelect( selector, [ rootClientId ] );

	const dropTargetIndex = useBlockDropZone( {
		element: wrapperRef,
		rootClientId,
	} );

	const isAppenderDropTarget = dropTargetIndex === blockClientIds.length;

	return (
		<>
			{ blockClientIds.map( ( clientId, index ) => {
				const isBlockInSelection = hasMultiSelection
					? multiSelectedBlockClientIds.includes( clientId )
					: selectedBlockClientId === clientId;

				const isDropTarget = dropTargetIndex === index;

				return (
					<AsyncModeProvider
						key={ clientId }
						value={ ! isBlockInSelection }
					>
						<BlockListBlock
							rootClientId={ rootClientId }
							clientId={ clientId }
							// This prop is explicitely computed and passed down
							// to avoid being impacted by the async mode
							// otherwise there might be a small delay to trigger the animation.
							index={ index }
							className={ classnames( {
								'is-drop-target': isDropTarget,
								'is-dropping-horizontally':
									isDropTarget &&
									orientation === 'horizontal',
							} ) }
						/>
					</AsyncModeProvider>
				);
			} ) }
			{ blockClientIds.length < 1 && placeholder }
			<BlockListAppender
				tagName={ __experimentalAppenderTagName }
				rootClientId={ rootClientId }
				renderAppender={ renderAppender }
				className={ classnames( {
					'is-drop-target': isAppenderDropTarget,
					'is-dropping-horizontally':
						isAppenderDropTarget && orientation === 'horizontal',
				} ) }
			/>
		</>
	);
}

export function BlockListItems( props ) {
	// This component needs to always be synchronous as it's the one changing
	// the async mode depending on the block selection.
	return (
		<AsyncModeProvider value={ false }>
			<Items { ...props } />
		</AsyncModeProvider>
	);
}
