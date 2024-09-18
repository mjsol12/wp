import { useBlockProps } from "@wordpress/block-editor";
import apiFetch from "@wordpress/api-fetch";
import { useState, useEffect } from "react";
import { Button } from "@wordpress/components";

import MCPCard from "../components/card";
import MCPModal from "../components/modal";

import "../styles/editor.scss";

export default function Edit(props) {
  const { attributes, setAttributes } = props;
  const blockProps = useBlockProps();

  const [isModalOpen, setIsModalOpen] = useState(false);
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

  const handleSearchChange = (value) => setSearchTerm(value);

  const openModal = () => {
    fetchPosts("");
    setIsModalOpen(true);
  };

  const closeModal = () => setIsModalOpen(false);

  return (
    <div {...blockProps}>
      {isModalOpen && (
        <MCPModal
          attributes={attributes}
          closeModal={closeModal}
          mcp={mcp}
          loading={loading}
          setAttributes={setAttributes}
          handleSearchChange={handleSearchChange}
        />
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
