# Simple Library & Analytics Dashboard System

A simple web application designed to manage library operations, track active borrowed books histories, 
and gives a real-world key metrics.

## System Architecture & Database Design

### 1. One-to-Many Relationship
The system operates on a relational model between independent borrowers profiles and book transaction entries:
* **Parent Entity (`borrowers` table):** Holds the data for library borrowers (`borrower_id` PRIMARY KEY, names, contact references, home addresses). 
* **Child Entity (`books` table):** Tracks active, borrowed books to an individual borrower (`book_id` PRIMARY KEY, title, category, tracking stamps). 

A borrowed book transaction record cannot exist inside the database without being actively tied to a valid and accountable borrower profile record.
