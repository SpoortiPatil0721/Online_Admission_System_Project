function goToNext() {
    const cat = document.getElementById("category").value;
    const subcat = document.getElementById("subcategory").value;
    
    if (!cat || !subcat) {
      alert("Please select both category and subcategory.");
      return;
    }
  
    // Navigate to next page only if both values are selected
    window.location.href = "program-details.html";
  }
  