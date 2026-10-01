import java.io.File;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;
import org.xml.sax.ErrorHandler;
import org.xml.sax.SAXException;
import org.xml.sax.SAXParseException;

/** Проверяет XML-документ на соответствие внешнему DTD. */
public final class ValidateDtd {
    private ValidateDtd() {
    }

    public static void main(String[] args) {
        String fileName = args.length == 1 ? args[0] : "Videoteka.xml";

        try {
            DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
            factory.setValidating(true);
            factory.setNamespaceAware(false);
            factory.setFeature("http://apache.org/xml/features/nonvalidating/load-external-dtd", true);

            DocumentBuilder builder = factory.newDocumentBuilder();
            builder.setErrorHandler(new ValidationErrorHandler());
            Document document = builder.parse(new File(fileName));
            Element root = document.getDocumentElement();
            NodeList films = root.getElementsByTagName("film");

            System.out.println("DTD-валидация успешно пройдена.");
            System.out.println("Файл: " + fileName);
            System.out.println("Корневой элемент: " + root.getTagName());
            System.out.println("Количество фильмов: " + films.getLength());
        } catch (Exception exception) {
            System.err.println("DTD-валидация не пройдена для файла: " + fileName);
            System.err.println(exception.getMessage());
            System.exit(1);
        }
    }

    private static final class ValidationErrorHandler implements ErrorHandler {
        @Override
        public void warning(SAXParseException exception) {
            System.err.println(format("Предупреждение", exception));
        }

        @Override
        public void error(SAXParseException exception) throws SAXException {
            throw new SAXException(format("Ошибка DTD", exception));
        }

        @Override
        public void fatalError(SAXParseException exception) throws SAXException {
            throw new SAXException(format("Критическая ошибка XML", exception));
        }

        private String format(String level, SAXParseException exception) {
            return level + " (строка " + exception.getLineNumber() + ", столбец "
                + exception.getColumnNumber() + "): " + exception.getMessage();
        }
    }
}
