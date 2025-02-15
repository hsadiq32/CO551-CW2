window.addEventListener('load', function() {
    document.getElementById('fileinput').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            var img = document.getElementById('userImg');
            img.onload = () => {
                URL.revokeObjectURL(img.src);  // no longer needed, free memory
            }
            img.src = URL.createObjectURL(this.files[0]); // set src to blob url
        }
    });
  });