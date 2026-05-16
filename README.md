# Library & Analytics Dashboard System

A simple web application tailored to manage library operations, track active borrowed books histories, 
and deliver real-world situational key metrics.

## System Architecture & Database Design

### 1. One-to-Many Relationship
The system operates on a foundational relational model between independent borrowers profiles and book transaction entries:
* **Parent Entity (`borrowers` table):** Holds master metadata for library patrons (`borrower_id` PRIMARY KEY, names, contact references, home addresses). Profiles exist independently in the system database.
* **Child Entity (`books` table):** Tracks active, checked-out assets mapped to an individual user (`book_id` PRIMARY KEY, title, category, tracking stamps). 

### 2. Relational Logic
Every Borrowed Book entry explicitly references a parent profile via a Foreign Key (`borrower_id`). 
* **Dependent Lifecycle:** A borrowed book transaction record cannot logically exist inside the database without being actively tied to a valid and accountable borrower profile record.
---

## Key Functional Features

* ** Secure Session Architecture:** Includes a multi-tier authentication system (`login.php`, `register.php`) utilizing strict administrative access and system state restrictions via global PHP `$_SESSION` controls.
* ** Real-Time KPI Analytics Cards:** A full-color, CSS grid-driven key performance metrics panel processing live SQL aggregate database evaluations:
  * **Registered Borrowers:** Total headcount tracking current active member metrics.
  * **Books Checked Out:** Counts live, outstanding library circulation allocations.
  * **Total Books Returned:** Pulls data dynamically from activity logs tracking complete cycle returns.
* ** System Activity Audit Logs:** A system audit trail module logging all administrative actions (`CREATE`, `UPDATE`, `DELETE`, `SEARCH`) detailing *who* executed *what* data operation, and *when* it occurred for strict compliance tracking.
* ** High-Performance Fluid Layout:** Tailored using custom, responsive structural wrapper properties (`app-container`, flexbox splits, grid structures) that scale fluidly from extra-large screen desktop monitors down to standard narrow mobile layouts.
