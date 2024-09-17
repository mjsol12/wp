const { registerBlockType } = wp.blocks;

import metadata from "./block.json";
import Save from "./save";
import Edit from "./edit";

registerBlockType(metadata.name, {
  edit: Edit,
  save: Save,
});
