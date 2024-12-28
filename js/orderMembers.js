// get the list element that will be sortable
const sortableList = document.getElementById("sortable");

// variable to hold the currently dragged item
let draggedItem = null;

// add event listener for drag start
sortableList.addEventListener("dragstart", (e) => {
    // set the dragged item to the current target
    draggedItem = e.target;
    // add a CSS class to indicate the item is being dragged
    e.target.classList.add("dragging");

    // use a timeout to hide the item being dragged
    setTimeout(() => {
        e.target.style.display = "none";
    }, 0);
});


// add event listener for drag end
sortableList.addEventListener("dragend", (e) => {
    // remove the "dragging" class from the item
    e.target.classList.remove("dragging");

    // restore the display of the dragged item after a slight delay
    setTimeout(() => {
        e.target.style.display = "";
        draggedItem = null; //clear reference to item
    }, 0);
});


// add event listener for drag over (when an item is dragged over a valid drop target)
sortableList.addEventListener("dragover", (e) => {
    // prevent the default behavior to allow dropping
    e.preventDefault();
    // determine the element after which the dragged item should be placed
    const afterElement = getDragAfterElement(sortableList, e.clientY);

    // if there is no element after (dragged to the end), append the item at the end
    if (afterElement == null) {
        sortableList.appendChild(draggedItem);
    } else {
        // insert the dragged item before the found element
        sortableList.insertBefore(draggedItem, afterElement);
    }
});

// ------------HELPER FUNCTION TO FIND CORRECT POSITION-------------------

const getDragAfterElement = (container, y) => {
    const draggableElements = [
    // get all list items that are not currently being dragged
        ...container.querySelectorAll("li:not(.dragging)")
    ];

    // use reduce to find the closest element based on the mouse position
    return draggableElements.reduce(
        (closest, child) => {
            // get the bounding box of the current child element
            const box = child.getBoundingClientRect();
            // calculate distance from the center of the child to the mouse
            const offset = y - box.top - box.height / 2;
            // check if the offset is less than 0 (above center) and closer than the previous closest
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child }; // update closest with current child

            } else {
                return closest; //keep the previous closest element

            }
        },
        { offset: Number.NEGATIVE_INFINITY }
    ).element;
};


// ------SAVE CURRENT ORDER TO SERVER ---------


function saveOrder() {
     // get all list items in the sortable container
    const items = Array.from(document.querySelectorAll("#sortable li"));

     // map the items to an array of objects containing their IDs and display order
    const order = items.map((item, index) => ({
        member_id: item.getAttribute("data-id"),
        display_order: index + 1 
    }));

    // send the updated order to the server 
    fetch("saveOrder.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(order) // convert the order data to a JSON string
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Order updated successfully!");
            location.reload(); // refresg to reflect changes
        } else {
            alert("Failed to update order: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while saving the order.");
    });
}
