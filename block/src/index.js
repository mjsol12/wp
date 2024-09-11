import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import Edit from "./edit";

// Register Block
registerBlockType("my-plugin/mcp-block", {
  title: __("MCP Block", "my-plugin"),
  icon: "welcome-write-blog", // Choose a Dashicon
  category: "common",
  edit: Edit,
  save() {
    return null; // This block will be rendered dynamically on the front end
  },
});
