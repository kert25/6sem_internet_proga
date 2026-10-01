import java.io.File;
import javax.xml.XMLConstants;
import javax.xml.transform.stream.StreamSource;
import javax.xml.validation.Schema;
import javax.xml.validation.SchemaFactory;
import javax.xml.validation.Validator;
import org.xml.sax.SAXException;
import org.xml.sax.SAXParseException;

/** Проверяет XML-документ на соответствие XML Schema (XSD). */
public final class ValidateXsd {
    private ValidateXsd() {
    }

    public static void main(String[] args) {
        String xmlFile = args.length >= 1 ? args[0] : "Videoteka.xml";
        String xsdFile = args.length >= 2 ? args[1] : "Videoteka.xsd";

        try {
            SchemaFactory factory = SchemaFactory.newInstance(XMLConstants.W3C_XML_SCHEMA_NS_URI);
            factory.setProperty(XMLConstants.ACCESS_EXTERNAL_DTD, "");
            factory.setProperty(XMLConstants.ACCESS_EXTERNAL_SCHEMA, "");
            Schema schema = factory.newSchema(new File(xsdFile));
            Validator validator = schema.newValidator();
            validator.validate(new StreamSource(new File(xmlFile)));

            System.out.println("XSD-валидация успешно пройдена.");
            System.out.println("XML-файл: " + xmlFile);
            System.out.println("XSD-схема: " + xsdFile);
        } catch (SAXParseException exception) {
            System.err.println("XSD-валидация не пройдена для файла: " + xmlFile);
            System.err.println(format(exception));
            System.exit(1);
        } catch (SAXException exception) {
            System.err.println("XSD-валидация не пройдена для файла: " + xmlFile);
            System.err.println(exception.getMessage());
            System.exit(1);
        } catch (Exception exception) {
            System.err.println("Не удалось выполнить XSD-валидацию: " + exception.getMessage());
            System.exit(1);
        }
    }

    private static String format(SAXParseException exception) {
        return "Ошибка XSD (строка " + exception.getLineNumber() + ", столбец "
            + exception.getColumnNumber() + "): " + exception.getMessage();
    }
}
