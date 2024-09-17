import { useBlockProps } from "@wordpress/block-editor";
import { useSelect } from "@wordpress/data";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "../styles/editor.scss";
import MCPCard from "../components/card";

export default function Edit({ attributes }) {
  const { align } = attributes;
  const blockProps = useBlockProps({
    className: align ? `align${align}` : "",
  });

  // Fetch MCP posts using `useSelect`
  const posts = useSelect((select) => {
    return select("core").getEntityRecords("postType", "mcp", {
      per_page: -1,
    });
  }, []);

  // Handle loading and no posts found
  if (!posts) {
    return <p>Loading...</p>;
  }
  if (posts.length === 0) {
    return <p>No MCP posts found.</p>;
  }

  return (
    <div {...blockProps}>
      <div className="mcp-block">
        {posts.map((post) => (
          <MCPCard
            key={post.id}
            title={post.title.rendered}
            image={post.featured_media_url}
            description={post.excerpt.rendered}
            link={post.link}
          />
        ))}
      </div>
    </div>
  );
}
