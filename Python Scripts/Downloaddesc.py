import pandas as pd
from selenium import webdriver
from selenium.common.exceptions import NoSuchElementException

all_books = pd.read_json('book_best_001_025.jl', lines=True)

driver = webdriver.Firefox()

for i in range(0,594):
    print("book desc number:" + str(i) )
    driver.get(all_books["url"][i])
    try:
        if driver.find_element_by_class_name("modal__content").size != 0 : 
            driver.find_element_by_xpath("/html/body/div[3]/div/div/div[1]/button").click()
    except NoSuchElementException :
        print("No modal/media")
    driver.find_element_by_link_text("...more").click()
    txt = driver.find_element_by_id("description")
    text = txt.text

    f = open('description/desc' + str(i) + '.txt', 'w', encoding="utf-8")
    f.write(text)
    f.close
    print("Done! jumping to next one")
driver.close()
