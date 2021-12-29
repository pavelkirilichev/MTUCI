const bluer = document.querySelector('.bluer')
const editMain = document.querySelector('.edit__main')
const sectionList = document.querySelectorAll('.timetable__item');

sectionList.forEach((elem) => {
    elem.addEventListener('click', () => { 
        sectionList.forEach((elem) => {
            elem.classList.remove("active")
        })
        elem.classList.add("active")
        tableLoad(elem.textContent)
    })
})


function sendEdit() {
    let send_data = $(".edit__form").serialize()
    console.log(send_data)

    
    $.ajax({
        url: 'src/less_edit.php',
        type: "POST",
        dataType: "html",
        data: send_data,
        success: function(data) {
            bluer.style.display = "none"
            editMain.style.display = "none"
            tableLoad(data)
        }
    })
    
}

function loadEditWindow(lesson_id, day) {
    bluer.style.display = "block"
    editMain.style.display = "block"

    $.ajax({
        url: 'src/load_edit.php',
        type: "POST",
        data: {
            less_id: lesson_id,
            day: day
        },
        success: function(data) {
            $(".edit__main").html(data)

            const closeBtn = document.querySelector('.close__btn')
            closeBtn.addEventListener('click', () => { 
                editMain.style.display = "none"
                bluer.style.display = "none"
            })

            const editBtn = document.querySelector('#edit__send__btn')
            editBtn.addEventListener('click', () => { 
                sendEdit()
            })
        }
    })
}

function tableLoad(day) {
    $.ajax({
        url: 'src/table_load.php',
        type: "POST",
        data: {day: day},
        success: function(data) {
            $(".timetable__output").html(data)

            const lessonsList = document.querySelectorAll('.edit__btn');
            lessonsList.forEach((elem) => {
                elem.addEventListener('click', () => { 
                    loadEditWindow(elem.id, elem.name)
                })
            })
        }
    })
}

tableLoad("Понедельник")
