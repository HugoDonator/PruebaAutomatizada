import unittest
import time
import os
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import HtmlTestRunner

class CarsAppTest(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        options = webdriver.ChromeOptions()
        options.add_argument('--ignore-certificate-errors')
        options.add_argument('--ignore-ssl-errors')
        # options.add_argument('--headless')
        options.add_argument('--no-sandbox')
        options.add_argument('--disable-dev-shm-usage')
        
        # Configuracion para descargas
        prefs = {
            "download.default_directory": os.path.join(os.getcwd(), "downloads"),
            "download.prompt_for_download": False,
            "download.directory_upgrade": True,
            "plugins.always_open_pdf_externally": True
        }
        options.add_experimental_option("prefs", prefs)
        
        cls.driver = webdriver.Chrome(
            service=Service(ChromeDriverManager().install()),
            options=options
        )
        cls.driver.maximize_window()
        cls.base_url = "http://localhost/Cars"
        cls.screenshots_dir = "screenshots"
        cls.reports_dir = "reports"
        cls.downloads_dir = "downloads"
        
        os.makedirs(cls.screenshots_dir, exist_ok=True)
        os.makedirs(cls.reports_dir, exist_ok=True)
        os.makedirs(cls.downloads_dir, exist_ok=True)

        # Datos del personaje de prueba
        cls.test_character = {
            "nombre": "Mate",
            "color": "#00FF00",
            "tipo": "Mecanico",
            "nivel": "3",
            "foto": "https://i.pinimg.com/736x/fb/8b/6a/fb8b6a3555d3939074bad08c68208fe2.jpg",
            "nuevo_color": "#FF0000",
            "nuevo_nivel": "5"
        }

    def take_screenshot(self, name):
        """Toma una captura de pantalla y la guarda en el directorio 'screenshots'."""
        timestamp = time.strftime("%Y%m%d-%H%M%S")
        filename = f"{self.screenshots_dir}/{name}_{timestamp}.png"
        self.driver.save_screenshot(filename)
        return filename

    def safe_click(self, element):
        """Metodo seguro para hacer clic en elementos"""
        self.driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", element)
        time.sleep(1)
        try:
            element.click()
        except:
            self.driver.execute_script("arguments[0].click();", element)

    def test_1_capturar_index(self):
        """Test 1: Capturar pantalla inicial del index"""
        try:
            self.driver.get(f"{self.base_url}/index.php")
            WebDriverWait(self.driver, 20).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, "body"))
            )
            self.take_screenshot("pantalla_inicial_index")
        except Exception as e:
            self.take_screenshot("error_pantalla_inicial_index")
            raise

    def test_2_agregar_personaje(self):
        """Test 2: Agregar personaje Mate"""
        try:
            self.driver.get(f"{self.base_url}/agregar.php")
            
            # Llenar formulario
            WebDriverWait(self.driver, 20).until(
                EC.presence_of_element_located((By.ID, "nombre"))
            ).send_keys(self.test_character["nombre"])
            
            self.driver.find_element(By.ID, "color").send_keys(self.test_character["color"])
            self.driver.find_element(By.ID, "tipo").send_keys(self.test_character["tipo"])
            self.driver.find_element(By.ID, "nivel").send_keys(self.test_character["nivel"])
            self.driver.find_element(By.ID, "foto").send_keys(self.test_character["foto"])
            
            self.take_screenshot("formulario_agregar_personaje_lleno")
            
            # Enviar formulario
            submit_button = WebDriverWait(self.driver, 20).until(
                EC.element_to_be_clickable((By.CSS_SELECTOR, "button[type='submit']"))
            )
            self.safe_click(submit_button)
            
            # Verificar que se agrego
            WebDriverWait(self.driver, 20).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, ".alert-success"))
            )
            self.take_screenshot("personaje_agregado")
            
        except Exception as e:
            self.take_screenshot("error_agregar_personaje")
            raise

    def test_3_editar_personaje(self):
        """Test 3: Editar personaje Mate"""
        try:
            print("Iniciando prueba de edicion...")
            self.driver.get(f"{self.base_url}/index.php")
            self.take_screenshot("antes_de_editar_index")

            # Buscar el personaje
            character_card = None
            cards = WebDriverWait(self.driver, 30).until(
                EC.presence_of_all_elements_located((By.CSS_SELECTOR, ".car-card-item, .card, .character-card"))
            )
            
            print(f"Encontrados {len(cards)} cards de personajes")
            for i, card in enumerate(cards):
                card_text = card.text.lower()
                print(f"Card {i+1} texto: {card_text[:50]}...")
                if self.test_character["nombre"].lower() in card_text:
                    character_card = card
                    print("¡Personaje Mate encontrado!")
                    break

            self.assertIsNotNone(character_card, "No se encontro el personaje Mate en la lista")
            self.take_screenshot("personaje_encontrado")

            # Buscar boton de edicion
            edit_button = WebDriverWait(character_card, 20).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, "a[href*='editar']"))
            )
            print("Boton de edicion encontrado")

            # Hacer clic en editar
            self.safe_click(edit_button)
            self.take_screenshot("despues_de_clic_editar")

            # Editar campos
            color_input = WebDriverWait(self.driver, 30).until(
                EC.presence_of_element_located((By.ID, "color"))
            )
            color_input.clear()
            color_input.send_keys(self.test_character["nuevo_color"])
            print("Color actualizado")
            
            nivel_input = WebDriverWait(self.driver, 30).until(
                EC.presence_of_element_located((By.ID, "nivel"))
            )
            nivel_input.clear()
            nivel_input.send_keys(self.test_character["nuevo_nivel"])
            print("Nivel actualizado")
            
            self.take_screenshot("formulario_editar_personaje_lleno")

            # Guardar cambios
            save_button = WebDriverWait(self.driver, 30).until(
                EC.element_to_be_clickable((By.CSS_SELECTOR, "button[type='submit']"))
            )
            self.safe_click(save_button)
            print("Cambios guardados")

            # Verificar cambios
            WebDriverWait(self.driver, 30).until(
                lambda d: "index.php" in d.current_url.lower()
            )
            print("Edicion confirmada")
            self.take_screenshot("personaje_editado")

        except Exception as e:
            print(f"Error durante la edicion: {str(e)}")
            self.take_screenshot("error_editar_personaje")
            raise

    def test_4_generate_pdf(self):
        """Test 4: Generar PDF del personaje Mate"""
        try:
            print("\nIniciando prueba de generación de PDF...")

            # 1. Ir al index y buscar el personaje
            self.driver.get(f"{self.base_url}/index.php")
            WebDriverWait(self.driver, 30).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, "body"))
            )
            self.take_screenshot("antes_de_generar_pdf")

            # 2. Localizar el card del personaje
            character_card = None
            cards = WebDriverWait(self.driver, 30).until(
                EC.presence_of_all_elements_located(
                    (By.CSS_SELECTOR, ".car-card-item, .card, .character-card")
                )
            )

            for card in cards:
                if self.test_character["nombre"].lower() in card.text.lower():
                    character_card = card
                    print("Personaje encontrado para generar PDF")
                    break

            self.assertIsNotNone(character_card, "No se encontró el personaje para generar PDF")

            # 3. Localizar el botón PDF
            pdf_button = WebDriverWait(character_card, 30).until(
                EC.presence_of_element_located(
                    (By.CSS_SELECTOR, "a[href*='generar_pdf'], .btn-pdf, a.btn-success, [onclick*='pdf']")
                )
            )
            print("Botón PDF encontrado")

            # 4. Hacer clic en el botón PDF
            self.driver.execute_script("arguments[0].click();", pdf_button)
            print("Clic en botón PDF ejecutado")
            self.take_screenshot("despues_de_clic_pdf")

            # 5. Esperar un tiempo para que el PDF se descargue
            time.sleep(5)  # Ajusta el tiempo según sea necesario

            # 6. Verificar que el PDF se descargó
            pdf_files = [f for f in os.listdir(self.downloads_dir)
                         if f.lower().startswith("perfil_") and f.lower().endswith(".pdf")]

            print(f"Archivos PDF encontrados: {pdf_files}")
            self.assertGreater(len(pdf_files), 0, "No se encontró el PDF descargado")

        except Exception as e:
            print(f"Error durante generación de PDF: {str(e)}")
            self.take_screenshot("error_generar_pdf")
            raise

    def test_5_delete_character(self):
        from selenium.common.exceptions import TimeoutException
        from selenium.common.exceptions import TimeoutException, NoSuchElementException
        """Test 5: Eliminar personaje Mate - Versión mejorada"""
        try:
            print("\nIniciando prueba de eliminación mejorada...")

            # 1. Ir al index
            self.driver.get(f"{self.base_url}/index.php")
            WebDriverWait(self.driver, 30).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, "body"))
            )
            self.take_screenshot("antes_de_eliminar")

            # 2. Buscar el card del personaje con manejo de errores mejorado
            character_card = None
            try:
                cards = WebDriverWait(self.driver, 30).until(
                    EC.presence_of_all_elements_located(
                        (By.CSS_SELECTOR, ".car-card-item, .card, .character-card")
                    )
                )

                print(f"Encontrados {len(cards)} cards de personajes")
                for i, card in enumerate(cards):
                    card_text = card.text.lower()
                    print(f"Card {i+1}: {card_text[:50]}...")
                    if self.test_character["nombre"].lower() in card_text:
                        character_card = card
                        print("¡Personaje encontrado para eliminar!")
                        break

                if not character_card:
                    raise NoSuchElementException("No se encontró el personaje para eliminar")
                    
                self.take_screenshot("personaje_encontrado_para_eliminar")

            except Exception as e:
                self.take_screenshot("error_finding_character")
                raise AssertionError(f"Error buscando personaje: {str(e)}")

            # 3. Buscar botón de eliminar con espera inteligente
            try:
                delete_button = WebDriverWait(character_card, 15).until(
                    EC.element_to_be_clickable(
                        (By.CSS_SELECTOR, "a[href*='eliminar'], button.delete-btn")
                    )
                )
                print("Botón eliminar encontrado")
                self.take_screenshot("Boton eliminar encontrado")
            except TimeoutException:
                self.take_screenshot("Eror Boton eliminar no encontrado")
                raise AssertionError("No se encontró el botón de eliminar")

            # 4. Hacer clic en eliminar con JavaScript como fallback
            try:
                delete_button.click()
                print("Clic en eliminar ejecutado (método normal)")
            except Exception:
                self.driver.execute_script("arguments[0].click();", delete_button)
                print("Clic en eliminar ejecutado (con JavaScript)")
            
            time.sleep(1)  # Pequeña pausa para permitir que se procese el click

            # 5. Manejar la alerta con múltiples intentos
            alert_handled = False
            for attempt in range(2):  # Intentar hasta 2 veces
                try:
                    alert = WebDriverWait(self.driver, 3).until(EC.alert_is_present())
                    alert_text = alert.text
                    print(f"Texto de la alerta (intento {attempt+1}): {alert_text}")
                    alert.accept()
                    print("Alerta aceptada")
                    alert_handled = True
                    self.take_screenshot(f"intento_de_alerta_aceptado{attempt+1}")
                    break
                except TimeoutException:
                    print(f"Intento {attempt+1}: No apareció alerta inmediata")
                    if attempt == 0:
                        time.sleep(2)  # Esperar un poco más para el segundo intento
                    continue

            if not alert_handled:
                print("No se encontró alerta nativa después de 2 intentos")
                self.take_screenshot("no_se_encontró_alerta")

            # 6. Manejar el modal de SweetAlert con verificación mejorada
            modal_handled = False
            try:
                # Esperar con timeout más corto primero
                WebDriverWait(self.driver, 5).until(
                    EC.presence_of_element_located((By.CSS_SELECTOR, ".swal2-popup"))
                )
                print("Modal de SweetAlert encontrado")
                self.take_screenshot("swal_modal_encontrado")

                # Intentar hacer clic en confirmar
                confirm_button = WebDriverWait(self.driver, 5).until(
                    EC.element_to_be_clickable((By.CSS_SELECTOR, ".swal2-confirm"))
                )
                confirm_button.click()
                print("Confirmación aceptada")
                modal_handled = True
                self.take_screenshot("swal_modal_Confirmado")

                # Esperar breve para procesamiento
                time.sleep(2)
                
            except TimeoutException:
                print("No apareció modal de SweetAlert en 5 segundos")
                self.take_screenshot("sin_swal_modal")

            # 7. Verificación robusta de eliminación
            deletion_verified = False
            verification_method = ""

            # Método 1: Verificar redirección (si aplica)
            try:
                current_url = self.driver.current_url
                if "eliminar" in current_url or "confirm" in current_url:
                    print(f"Redirección detectada: {current_url}")
                    verification_method = "redirección"
                    deletion_verified = True
            except Exception as e:
                print(f"Error verificando URL: {str(e)}")

            # Método 2: Verificar desaparición del elemento
            if not deletion_verified:
                try:
                    WebDriverWait(self.driver, 10).until(
                        EC.invisibility_of_element_located(
                            (By.XPATH, f"//*[contains(translate(., 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), '{self.test_character['nombre'].lower()}')]")
                        )
                    )
                    verification_method = "desaparición del elemento"
                    deletion_verified = True
                except TimeoutException:
                    print("El elemento no desapareció en el tiempo esperado")
                    self.take_screenshot("elemento_aún_visible")

            # Método 3: Verificación directa en el DOM
            if not deletion_verified:
                try:
                    remaining_elements = self.driver.find_elements(
                        By.XPATH, 
                        f"//*[contains(translate(., 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), '{self.test_character['nombre'].lower()}')]"
                    )
                    if not remaining_elements:
                        verification_method = "búsqueda directa (0 elementos)"
                        deletion_verified = True
                    else:
                        print(f"Elementos encontrados después de eliminar: {len(remaining_elements)}")
                        self.take_screenshot("elementos_aún_presentes")
                except Exception as e:
                    print(f"Error en búsqueda directa: {str(e)}")

            if deletion_verified:
                print(f"¡Personaje eliminado con éxito! Verificado por: {verification_method}")
                self.take_screenshot("_eliminación_éxitosa")
            else:
                raise AssertionError("No se pudo verificar la eliminación del personaje")

        except Exception as e:
            print(f"Error durante eliminación: {str(e)}")
            self.take_screenshot("error_eliminar_Personaje")
            raise

    @classmethod
    def tearDownClass(cls):
        cls.driver.quit()

# Solucion para el error de HtmlTestRunner
class CustomHTMLTestRunner(HtmlTestRunner.HTMLTestRunner):
    def run(self, test, **kwargs):
        result = super().run(test, **kwargs)
        return result

if __name__ == "__main__":
    unittest.main(
        testRunner=CustomHTMLTestRunner(
            output='reports',
            report_title='Pruebas Automatizadas - Aplicacion Cars',
            report_name='ReportePruebas',
            combine_reports=True,
            add_timestamp=True,
            verbosity=2
        )
    )