
# **Vet Clinic Management System**

 **Descriere Generală**
"Vet Clinic Management System" este o aplicație web dezvoltată pentru gestionarea programărilor și a informațiilor legate de clienții unui cabinet veterinar. Aplicația permite autentificarea utilizatorilor, crearea de conturi noi, gestionarea programărilor și vizualizarea acestora sub formă de calendar. Administratorul poate vizualiza toate programările efectuate de către utilizatori într-un format asemănător cu cel al Google Calendar, ceea ce facilitează organizarea și planificarea activităților zilnice.

Aplicația utilizează tehnologia PHP pentru partea de backend, împreună cu o bază de date MySQL gestionată prin PDO.

---

 **Funcționalități Principale Implementate**

 **Autentificare și Înregistrare Utilizatori**
- Utilizatorii pot crea conturi noi și se pot autentifica în aplicație.
- Utilizatorii autentificați pot adăuga, edita și șterge programări.
- Datele utilizatorilor sunt gestionate securizat în baza de date.

 **Dashboard pentru Utilizatori**
- După autentificare, utilizatorii pot accesa un dashboard personal în care își pot vedea toate programările.
 **Funcționalitatea de Adăugare Programare**
- Utilizatorii pot adăuga programări pentru animalele lor, specificând:
  - Nume animal
  - Nume proprietar
  - Data și ora programării
  - Note suplimentare
- Sistemul verifică suprapunerile pentru a evita conflictele de programare.

 **Funcționalitatea de Editare Programare**
- Utilizatorii pot edita programările existente dacă au nevoie să modifice detalii.

**Funcționalitatea de Ștergere Programare**
- Utilizatorii pot șterge programările care nu mai sunt necesare.

 **Calendar Vizual pentru Administrator**
- Administratorul are acces la un calendar care afișează toate programările efectuate de toți utilizatorii.
- Calendarul este structurat pe zile și ore, cu programările afișate pe intervale orare exacte.
- Calendarul permite selecția lunii și anului pentru a vizualiza programările din perioade diferite.



**Structura Proiectului**
```
vet_clinic/
├── app.php            # Fișier principal pentru gestionarea autentificării și programărilor
├── calendar.php       # Fișier separat pentru vizualizarea programărilor sub formă de calendar
├── README.md          # Documentația proiectului
└── database.sql       # Script SQL pentru crearea bazei de date și tabelelor necesare
```



`
