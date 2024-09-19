## My Custom Plugin

**Version**: 1.0  
**Author**: Your Name  
**License**: GPLv2 or later

---

### Description

**My Custom Plugin** adds a custom post plugin in your site. Perfect simulation for displaying and listing a object cards.

---

### Installation

1. Upload the plugin folder to the `/wp-content/plugins/` directory or upload the ZIP file via **Plugins > Add New > Upload Plugin**.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. **MCP** tab will automatically appear in the left nav.

---

### Usage

1. Create Your Own MCP: Click on the "Add MCP" button. This will navigate you to a simple post editor where you can create and customize your MCP.
2. Link Your MCP as a Block: Once you’ve created your MCP, you can easily link it as a block in any content area on your page where you want it to appear.

### Blocks

1. MCP Card: This block displays a single MCP card. It references only one MCP and allows for focused content display.
2. MCP List: This block generates a list of all MCPs available on your site, providing an overview of all your MCPs in a structured format.

## Development Mode

1. During development, use the command "npm run start" to enable real-time output and preview your changes as you code.
2. Once development is complete, run "npm run build" to generate the production-ready build files.

### Zip

1. In the same project directory, execute the command npm run plugin-zip. Ensure that the build is prepared for production by running npm run build first.
2. The resulting zip file will contain the same content as the initial .php file, ready for deployment.
