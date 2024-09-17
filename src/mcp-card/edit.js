import { useBlockProps } from "@wordpress/block-editor";
import apiFetch from "@wordpress/api-fetch";
import { useState, useEffect } from "react";
import { Button, Modal, TextControl, Spinner } from "@wordpress/components";

import MCPCard from "../components/card";

import "../styles/editor.scss";

export default function Edit(props) {
  const { attributes, setAttributes } = props;
  const blockProps = useBlockProps();

  const [isModalOpen, setIsModalOpen] = useState(false);
  const [search, setSearchTerm] = useState("");
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(false);

  const fetchPosts = (searchTerm) => {
    setLoading(true);
    apiFetch({ path: `/wp/v2/mcp?search=${searchTerm}` })
      .then((data) => {
        setPosts(data);
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

  const handleSearchChange = (value) => setSearchTerm(value);

  const openModal = () => {
    fetchPosts("");
    setIsModalOpen(true);
  };

  const closeModal = () => setIsModalOpen(false);

  return (
    <div {...blockProps}>
      {isModalOpen && (
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
              {posts.map((post) => (
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
      )}
      <div style={{ position: "absolute" }}>
        <Button variant="primary" onClick={openModal}>
          MCP lookup
        </Button>
      </div>
      <MCPCard
        image={attributes.image}
        title={attributes.title}
        link={attributes.link}
      />
    </div>
  );
}
