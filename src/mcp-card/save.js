import "../styles/editor.scss";
import MCPCard from "../components/card";

export default function Save(props) {
  const { attributes } = props;
  console.log(attributes);
  const alignClass = attributes["align"] ? attributes["align"] : "";

  return (
    <div className={alignClass}>
      <MCPCard
        image={attributes.image}
        title={attributes.title}
        link={attributes.link}
      />
    </div>
  );
}
