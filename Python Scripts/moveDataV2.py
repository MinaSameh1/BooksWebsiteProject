import getpass
from random import randint,uniform
import pandas as pd
from mysql.connector import connect,Error
import json
import simplejson

all_books = pd.read_json('BookData/book_best_001_025.jl', lines=True, encoding='utf-8')


try:
    # First connect to db
            #password=getpass.getpass("Enter Password:"),
    connection = connect( host='localhost', user='root', database='project')
# Check if connection is working
    print("Connected to DB")
    cur = connection.cursor(prepared=True)
# Loop the data to insert it 
    for i in range(0,584):
        # Prepare description file
        desc = open("./description/desc (" + str(i+1) + ").txt", "r", encoding="utf-8")
# Check if it has awards or not (This is due to it being emtpy then it returns float and string if it has awards).
        if isinstance(all_books["awards"][i], float): 
            awards_num = 0
        else: 
            awards_num = len(all_books["awards"][i])
        # Prepare them for entry in their right table
        awards = all_books["awards"][i]
        genres = all_books["genres"][i]
        characters = all_books["characters"][i]
        places = all_books["places"][i]
# Tuple Data Set 
        data = (
                all_books["title"][i],
                all_books["author"][i],
                all_books["num_pages"][i],
				# Get a random number between 1 and 4000
                randint(1,4000),
                all_books["language"][i],
                awards_num,
                # Get a random number between 20 and 75
                round(uniform(20.00,75.75),2),
                all_books["publish_date"][i],
                0,
                all_books["series"][i],
                all_books["original_publish_year"][i],
                "covers/cover" + str(i) + ".png",
                str(desc.read())
                )
        # INSERT:
        # The book itself
        SQLINSERT = "INSERT INTO Books(title,author,total_pages,SelerID,Lang,awards_num,price,published_date,bookType,series,original_publish,cover,description) VALUES( %s, %s,  %s,  %s,  %s,  %s,  %s,  %s,  %s,  %s,  %s,  %s, %s )"
        cur.execute( SQLINSERT , data )
        cur.execute("SET FOREIGN_KEY_CHECKS=0")
        print("BOOK INSERTED!")
# Get BOOK ID
        cur.execute("SELECT BookID from books ORDER BY BookID DESC LIMIT 1")
        result = cur.fetchall()
        bookID = result[0][0]
# Book Genre
# Check that its not empty first
        if not isinstance(genres, float): 
            for genre in genres:
                # Get the genre ID
                cur.execute("SELECT * FROM genres WHERE genreName=%s",(genre, ))
                genreID = cur.fetchall()
# If no genre found add it 
                if len(genreID) == 0:
                    cur.execute("INSERT INTO genres(genreName) VALUES(%s)", (genre,))
                    cur.execute("SELECT * FROM genres WHERE genreName=%s",(genre, )) 
                    genreID = cur.fetchall()
                bookgenre = ( bookID, genreID[0][0] )
                cur.execute("INSERT INTO BooksGenres VALUES(%s,%s)",bookgenre)
        # Character in book
        if not isinstance(characters, float): 
            for character in characters:
                char = ( character, bookID )
                cur.execute("INSERT INTO BooksCharacters VALUES(%s,%s)",char)
        # awards of book
        if awards_num > 0 :
            for award in awards:
                aw= ( award, bookID )
                cur.execute("INSERT INTO BooksAwards VALUES(%s,%s)",aw)
        # Place of book
        if not isinstance(places, float): 
            for place in places:
                plce = ( place, bookID )
                cur.execute("INSERT INTO BooksPlaces VALUES(%s,%s)",plce)
# Confirm
        cur.execute("SET FOREIGN_KEY_CHECKS=1")
        connection.commit()
# Print OK
        desc.close
        print("INSERT NUMBER " + str(i) + " IS SUCCESSFUL!")
except Error as ERROR:
    print(ERROR)
