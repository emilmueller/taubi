
    function initTab(){
        
        // Call the function to load books and then render them
        loadBooks().then(loadedBooks => {
        books = loadedBooks; // Store the loaded books
        renderBooks(books);  // Render books after loading
        });

        const addBookButton = document.getElementById('addBookButton');

        if (addBookButton) {
        addBookButton.addEventListener('click', function () {
            window.location = 'scan_barcode.php';
        });
        }

        const bookCardsContainer = document.getElementById('bookCards');
        const searchInput = document.getElementById('searchInput');
        const fachFilter = document.getElementById('fachFilter');
        // Filter on input or dropdown change
        searchInput.addEventListener('input', filterBooks);
        fachFilter.addEventListener('change', filterBooks);
    }
    
    
    

    // Function to load books
    async function loadBooks() {
      try {
        const response = await fetch('/api/get_books.php');
        const data = await response.json();

        // Transform API data to match your book object structure
        const books = data.map(book => ({
            id: book.id,
          title: book.title,
          author: book.author,
          description: `${book.publisher}, ${book.book_condition}, ${book.language}, ${book.pages} pages`, // or customize this
          image_url: book.image_url,
          pages: book.pages,
          book_condition: book.book_condition,
          price: book.price,
          date_published: book.date_published,
          language: book.language,
          isbn: book.isbn,
          seller: book.sold_by || '0', // fallback if no seller
          seller_name: book.seller_name, // if your API doesn't return seller name
          tags: book.tags,
          tag_ids: book.tag_ids
        }));

        //console.log(books);
        return books;
      } catch (error) {
        console.error('Error loading books:', error);
      }
    }

    function escapeForHtmlAttr(str) {
      //console.log(str.replace(/'/g, ""));
      return str.replace(/'/g, "");
    }

    // Function to render books
    function renderBooks(filteredBooks) {
      bookCardsContainer.innerHTML = '';
      filteredBooks.forEach(book => {
        const card = document.createElement('div');
        card.classList.add('col');
        card.innerHTML = `
          <div class="card h-100">
            <img src="${book.image_url}" class="card-img-top small_image" alt="Buchbild">
            <div class="card-body">
                <h5 class="card-title">${book.title}</h5>
                <!--<p class="card-text">${book.description}</p>-->
                <p class="text-muted">Autor: ${book.author}</p>
                <p class="text-muted" style="display:none">ISBN: ${book.isbn}</p>
                <p class="text-muted">Zustand: ${book.book_condition}</p>
                <p class="text-muted">Preis: ${book.price}</p>
                <p class="text-muted">Anbieter:in: ${book.seller_name}</p>
                <p class="text-muted">Fächer: ${book.tags}</p>
            </div>
            <div class="card-footer">
              
              <button class="btn btn-danger float-end" onclick="delete_book(${book.id})"><span class="bi bi-trash"></span></button> <!-- delete Button -->
              <button class="btn btn-success float-end me-1" onclick="edit_book(${book.id})"><span class="bi bi-pencil"></span></button> <!-- edit Button -->
            </div>
          
          </div>
        `;
        bookCardsContainer.appendChild(card);
      });
    }

     // Function to filter books based on search input and selected Fach
    function filterBooks() {
      const query = searchInput.value.trim().toLowerCase();
      const selectedFach = fachFilter.value;
      

      const filtered = books.filter(book => {
        const matchesQuery = book.title.toLowerCase().includes(query)
          || book.author.toLowerCase().includes(query)
          || book.isbn.toLowerCase().includes(query)
          || book.description.toLowerCase().includes(query)
          || (Array.isArray(book.tags) && book.tags.length>0 && book.tags.some(tag => typeof tag === 'string' && tag.toLowerCase().includes(query)));
          

        const matchesFach = !selectedFach || (book.tag_ids && book.tag_ids.includes(selectedFach));
        return matchesQuery && matchesFach;
      });

      renderBooks(filtered);
    }

   

    

   

   

   async function loadTags() {
    try {
      const response = await fetch('/api/get_tags.php');
      const data = await response.json();

      const fachFilter = document.getElementById('fachFilter');

      data.tags.forEach(tag => {
        const option = document.createElement('option');
        option.value = tag.id;
        option.textContent = tag.name;
        fachFilter.appendChild(option);
      });
    } catch (error) {
      console.error('Fehler beim Laden der Tags:', error);
    }
  }

  

  

    // Function to handle the "Delete" button 
    function delete_book(book_id) {
        fetch("/api/delete_book.php?id="+book_id)
        .then(response => response.json())
        .then(result => {
            if(result.success){
                loadBooks().then(loadedBooks => {
                    renderBooks(loadedBooks);  // Render books after loading
                });

            }

        });
            
    }

    // Function to handle the "Edit" button 
    function edit_book(book_id) {
        sessionStorage.setItem('lastTab', 'books');
        sessionStorage.setItem('lastSite','../app/admin.php');
        window.open("/app/getbook.php?book_id="+book_id+"&action=db_search&redirect=../app/admin.php?tab=books", "_self"); // edit 
        
    }
  



