import java.io.File;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

/** Проверяет, что XML-документ видеотеки является корректно сформированным. */
public final class ValidateXml {
    private ValidateXml() {
    }

    public static void main(String[] args) {
        String fileName = args.length == 1 ? args[0] : "Videoteka.xml";

        try {
            // Защита от загрузки внешних сущностей: проверяется только локальный XML-файл.
            DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
            factory.setFeature("http://apache.org/xml/features/disallow-doctype-decl", true);
            factory.setFeature("http://xml.org/sax/features/external-general-entities", false);
            factory.setFeature("http://xml.org/sax/features/external-parameter-entities", false);
            factory.setXIncludeAware(false);
            factory.setExpandEntityReferences(false);

            DocumentBuilder builder = factory.newDocumentBuilder();
            Document document = builder.parse(new File(fileName));
            Element root = document.getDocumentElement();
            NodeList films = root.getElementsByTagName("film");

            if (!"videotheque".equals(root.getTagName())) {
                throw new IllegalArgumentException("Корневой элемент должен называться videotheque.");
            }

            System.out.println("XML-документ корректно сформирован.");
            System.out.println("Корневой элемент: " + root.getTagName());
            System.out.println("Количество фильмов: " + films.getLength());
        } catch (Exception exception) {
            System.err.println("Ошибка проверки XML: " + exception.getMessage());
            System.exit(1);
        }
    }
}
