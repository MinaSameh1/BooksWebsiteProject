
from faker import Faker
from datetime import date
import hashlib
from mysql.connector import connect,Error

fake = Faker()

Pass = "1234"
encodedPass= hashlib.sha256(Pass.encode())

try:
	for i in range(0,6001):
		today = date.today()
		birthDate = fake.date_of_birth(minimum_age=16, maximum_age=60)
		# password=getpass.getpass("Enter Password:"),
		connection = connect( host='localhost', user='root', database='Project')
		cur = connection.cursor(prepared=True)
		data = (
			fake.profile(fields=['username'])['username'],
			encodedPass.hexdigest(),
			fake.name(),
			fake.phone_number(),
			(today.year - birthDate.year - ((today.month, today.day) < (birthDate.month, birthDate.day))),
			birthDate,
			0,
			fake.free_email(),
			fake.address(),
			0
			)
		cur.execute(
			"INSERT INTO users(userName,password,Name,phoneNumber,age,DOB,UserType,Email,Address,UserBlocked) VALUES( %s,%s,%s,%s,%s,%s,%s,%s,%s,%s )" 
			, data 
			)
		# Confirm
		connection.commit()
		print("Done! Number:" + str(i))

except Error as ERROR:
    print(ERROR)