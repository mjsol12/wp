import React from "react";
import { Dashicon } from "@wordpress/components";

export default function MCPCard(props) {
  const { image, link, title, description } = props;
  return (
    <div className="mcp-block-card">
      {image ? (
        <img src={image} alt={image}></img>
      ) : (
        <img src="https://via.placeholder.com/300x200" alt="Placeholder"></img>
      )}
      <div className="mcp-category">
        <Dashicon
          icon="format-image"
          className="dashicons dashicons-format-image"
        />
        Object
      </div>
      <div className="mcp-title">
        <a href={link} target="_blank" rel="noopener noreferrer">
          {title}
        </a>
      </div>
      {description && (
        <div className="mcp-description">
          <p
            dangerouslySetInnerHTML={{
              __html: description,
            }}
          />
        </div>
      )}
    </div>
  );
}
