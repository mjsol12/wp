import React from "react";
import { Button, Modal, TextControl, Spinner } from "@wordpress/components";

export default function MCPModal(props) {
  const {
    closeModal,
    mcp,
    attributes,
    handleSearchChange,
    loading,
    setAttributes,
  } = props;
  return (
    <Modal
      title="Select a MCP"
      onRequestClose={closeModal}
      shouldCloseOnClickOutside={true}
    >
      <TextControl
        label="Search"
        value={attributes.searchTerm}
        onChange={handleSearchChange}
      />
      {loading ? (
        <Spinner />
      ) : (
        <ul>
          {mcp.map((post) => (
            <li key={post.id}>
              <Button
                onClick={() => {
                  setAttributes({
                    id: post.id,
                    title: post.title.rendered,
                    image: post.featured_media_url,
                    link: post.link,
                  });
                  closeModal();
                }}
              >
                {post.title.rendered}
              </Button>
            </li>
          ))}
        </ul>
      )}
    </Modal>
  );
}
