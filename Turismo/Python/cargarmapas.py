from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
import mysql.connector
import time
from selenium.webdriver.support import expected_conditions as EC
PATH = "C:\Program Files (x86)\chromedriver.exe"
mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="",
  database="europa"
)
mycursor = mydb.cursor()
mycursor.execute("SELECT * FROM destinos where destinos.destinos_id > 37")

myresult = mycursor.fetchall()


driver = webdriver.Chrome(PATH)

#https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d317718.69319292053!2d-0.3817765050863085!3d51.528307984912544!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a00baf21de75%3A0x52963a5addd52a99!2sLondres%2C%20Reino%20Unido!5e0!3m2!1ses!2sar!4v1604979238981!5m2!1ses!2sar

for x in myresult:
    driver.get("https://www.google.co.uk/maps/place/" + x[1])
    menu = WebDriverWait(driver, 10).until(
    EC.presence_of_element_located((By.ID, 'content-container'))
    )
    time.sleep(2)
    men = menu.find_element_by_class_name('searchbox-button')
    men.click()
    time.sleep(2)
    compartir = driver.find_element(By.XPATH, "//*[text()= 'Compartir o insertar el mapa']")
    buton = compartir.find_element_by_xpath('..')
    buton.click()
    time.sleep(5)
    incorporar = driver.find_element(By.XPATH, "//*[text()= 'Insertar un mapa']")
    incorporar.click()
    time.sleep(5)
    inp = driver.find_element_by_css_selector('.section-embed-map-input').get_attribute('value')
    start = inp.find('h')
    end = inp.find('"',15)
    mapa = inp[start:end]
    try:
        mycursor.execute(f'UPDATE destinos SET destinos.map = "{mapa}" where destinos.destinos_id = {x[0]}')
        mydb.commit()
    except:
        print('error')

 



