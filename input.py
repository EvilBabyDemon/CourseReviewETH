import sqlite3

con = sqlite3.connect("CourseReviews.db")
cur = con.cursor()

f = open("numbersAndNames.txt", "r", encoding='utf-8') 

lines = f.readlines()

course_list = []

for line in lines:
    arr = line.split(" ", 1)
    course_list.append((arr[0], arr[1].strip("\n")))

cur.executemany("INSERT into COURSES values (?, ?)", course_list)
con.commit()
con.close()