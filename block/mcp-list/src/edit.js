import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
	const { align } = attributes;
	const blockProps = useBlockProps( {
		className: align ? `align${ align }` : '',
	} );

	// Fetch MCP posts using `useSelect`
	const posts = useSelect( ( select ) => {
		return select( 'core' ).getEntityRecords( 'postType', 'mcp', {
			per_page: -1,
		} );
	}, [] );

	// Handle loading and no posts found
	if ( ! posts ) {
		return <p>Loading...</p>;
	}
	if ( posts.length === 0 ) {
		return <p>No MCP posts found.</p>;
	}

	// Render list of MCP posts
	return (
		<div { ...blockProps }>
			<div className="mcp-block">
				{ posts.map( ( post ) => (
					<div className="mcp-block-card" key={ post.id }>
						{ post.featured_media ? (
							<img
								src={ post.featured_media_url }
								alt={ post.title.rendered }
							/>
						) : (
							<img
								src="https://via.placeholder.com/300x200"
								alt="Placeholder"
							/>
						) }
						<div className="mcp-category">
							<span className="dashicons dashicons-format-image" />{ ' ' }
							Object
						</div>
						<div className="mcp-title">
							<a
								href={ post.link }
								target="_blank"
								rel="noopener noreferrer"
							>
								{ post.title.rendered }
							</a>
						</div>
						<div className="mcp-description">
							{ post.excerpt.rendered ? (
								<p
									dangerouslySetInnerHTML={ {
										__html: post.excerpt.rendered,
									} }
								/>
							) : (
								<p>No description available.</p>
							) }
						</div>
					</div>
				) ) }
			</div>
		</div>
	);
}
