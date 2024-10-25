import { useBlockProps } from "@wordpress/block-editor";
import apiFetch from "@wordpress/api-fetch";
import { useState, useEffect } from "react";
import { Button } from "@wordpress/components";

import "./editor.scss";

export default function Edit(props) {
  const { attributes, setAttributes } = props;
  const blockProps = useBlockProps();

  const [search, setSearchTerm] = useState("");
  const [mcp, setMcp] = useState([]);
  const [loading, setLoading] = useState(false);

  const fetchPosts = (searchTerm) => {
    setLoading(true);
    apiFetch({ path: `/wp/v2/mcp?search=${searchTerm}` })
      .then((data) => {
        setMcp(data);
        setLoading(false);
      })
      .catch(() => {
        setLoading(false);
      });
  };

  useEffect(() => {
    const timeout = setTimeout(() => {
      fetchPosts(search);
    }, 500);

    return () => clearTimeout(timeout);
  }, [search]);

  // display the attribute

  // set the attribute
  const setAttribute = (post) => {
    setAttributes({
      id: post.id,
      title: post.title.rendered,
      image: post.featured_media_url,
      link: post.link,
    });
  };

  return (
    <div {...blockProps}>
      {loading ? (
        <Spinner />
      ) : (
        <ul>
          {mcp.map((post) => (
            <li key={post.id}>
              <Button
                onClick={() => {
                  setAttribute(post);
                }}
              >
                {post.title.rendered}
              </Button>
            </li>
          ))}
        </ul>
      )}
      <div
        className="mcp-block-card"
        tabindex="0"
        aria-label={`Card for ${title}`}
      >
        {attributes.image ? (
          <img src={attributes.image} alt={attributes.image}></img>
        ) : (
          <img
            src="https://via.placeholder.com/300x200"
            alt="Placeholder"
          ></img>
        )}
        <div className="mcp-category">
          <Dashicon
            icon="format-image"
            className="dashicons dashicons-format-image"
          />
          Object
        </div>
        <div className="mcp-title">
          <a href={attributes.link} target="_blank" rel="noopener noreferrer">
            {attributes.title}
          </a>
        </div>
      </div>
    </div>
  );
}
