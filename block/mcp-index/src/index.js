// block.js

const { registerBlockType } = wp.blocks;
const { TextControl, SelectControl } = wp.components;
const { useState, useEffect } = wp.element;
const { apiFetch } = wp;

import metadata from "./block.json";

registerBlockType(metadata.name, {
  edit: (props) => {
    const { attributes, setAttributes } = props;
    const [posts, setPosts] = useState([]);

    useEffect(() => {
      apiFetch({ path: "/wp/v2/mcp" }).then((data) => {
        setPosts(data);
      });
    }, []);

    const handlePostChange = (postId) => {
      setAttributes({ postId: parseInt(postId) });
      const selectedPost = posts.find((post) => post.id === parseInt(postId));
      if (selectedPost) {
        setAttributes({ postTitle: selectedPost.title.rendered });
      }
    };

    return (
      <div>
        <SelectControl
          label="Select a Post"
          value={attributes.postId}
          options={posts.map((post) => ({
            label: post.title.rendered,
            value: post.id,
          }))}
          onChange={handlePostChange}
        />
        <TextControl
          label="Post Title"
          value={attributes.postTitle}
          onChange={(title) => setAttributes({ postTitle: title })}
        />
      </div>
    );
  },
  save: (props) => {
    const { attributes } = props;

    return (
      <div>
        <h2>{attributes.postTitle}</h2>
        {/* You can add more fields or markup as needed */}
      </div>
    );
  },
});
