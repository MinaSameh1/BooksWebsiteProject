import pandas as pd
from selenium import webdriver
from selenium.common.exceptions import NoSuchElementException
import requests

all_books = pd.read_json('book_best_001_025.jl', lines=True)

driver = webdriver.Firefox()

for i in range(0,len(all_books["url"])):
    print("Doing number:" + str(i) )
    # Get URL of book
    driver.get(all_books["url"][i])
    # Cover Image!
    img = driver.find_element_by_id("coverImage")
    src = img.get_attribute('src')  
    # in case there is a popUp
    try:
        if driver.find_element_by_class_name("modal__content").size != 0 : 
            driver.find_element_by_xpath("/html/body/div[3]/div/div/div[1]/button").click()
    # No pop up
    except NoSuchElementException :
        print("No modal/media")
    # Click on more
    driver.find_element_by_link_text("...more").click()
    # Get Description
    txt = driver.find_element_by_id("description")
    text = txt.text
    # Open files to save description and Covers.
    desc = open('description/desc' + str(i) + '.txt', 'w', encoding="utf-8")
    desc.write(text)
    desc.close
    cover = open('covers/cover' + str(i) + '.png', 'wb')
    cover.write(requests.get(src).content)
    cover.close
    print("Done! jumping to next one")
# Close Driver
driver.close()
