import React from "react";

export default function MCPCard(props) {
  const { image, link, description, title } = props;
  return (
    <div className="mcp-block-card">
      {image ? (
        <img src={image} alt={title} />
      ) : (
        <img src="https://via.placeholder.com/300x200" alt="Placeholder" />
      )}
      <div className="mcp-category">
        <span className="dashicons dashicons-format-image"></span> Object
      </div>
      <div className="mcp-title">
        <a href={link} target="_blank" rel="noopener noreferrer">
          {title}
        </a>
      </div>
      <div className="mcp-description">
        {description ? (
          <p
            dangerouslySetInnerHTML={{
              __html: description,
            }}
          />
        ) : (
          <p>No description available.</p>
        )}
      </div>
    </div>
  );
}
