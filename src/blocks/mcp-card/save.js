import MCPCard from "../../components/card";

export default function Save(props) {
  const { attributes } = props;

  return (
    <div className={attributes["align"] ? attributes["align"] : ""}>
      <MCPCard
        image={attributes.image}
        title={attributes.title}
        link={attributes.link}
      />
    </div>
  );
}
