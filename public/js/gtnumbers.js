var gtnumbers = {
    let: lp = 0,

    addField: function() {
        lp ++;
        let newField = document.createElement('input');
        newField.setAttribute('type','text');
        newField.setAttribute('id','number'+lp);
        newField.setAttribute('name','number[]');
        newField.setAttribute('class','number');
        newField.setAttribute('size',30);
        newField.setAttribute('placeholder','Numer filmu');
        let newButton = document.createElement('button', 'SPRAWDŹ');
        newButton.setAttribute('name', 'check[]');
        newButton.setAttribute('id','check'+lp);
        newButton.setAttribute('class', 'check');
        newButton.setAttribute('data-lp', lp);
        newButton.innerHTML = 'SPRAWDŹ';
        numbers.appendChild(newField);
        numbers.appendChild(newButton);
        document.getElementById('generate').disabled = true;
    },
    removeField: function() {
        let input_tags = numbers.getElementsByTagName('input');
        if (input_tags.length > 2) {
            numbers.removeChild(input_tags[(input_tags.length) - 1]);
        }
        let button_tags = numbers.getElementsByTagName('button');
        if (button_tags.length > 2) {
            numbers.removeChild(button_tags[(button_tags.length) - 1]);
        }
    },
    approveInput: function(button) {
        let index = button.data('lp');
        let approvedInput = document.getElementById('number' + index);
        let approvedCheckBtn = document.getElementById('check' + index);
        approvedInput.setAttribute('disabled', true);
        approvedCheckBtn.setAttribute('disabled', true);
        if (gtnumbers.allApproved()) {
            document.getElementById('generate').disabled = false;
        }
    },
    allApproved: function() {
        let numberInputs = document.querySelectorAll('.number');
        let numberInputsCount = numberInputs.length;
        numberInputs.forEach(function(element) {
            if (element.disabled) {
                numberInputsCount--;
            }
        });
        if (numberInputsCount == 0) {
            return true;
        }
        return false;
    },
    generateOutput: function() {
        let numberInputs = document.querySelectorAll('.number');
        let outputString = '';
        numberInputs.forEach(function(element) {
            outputString += element.value + ";";
        });
        document.getElementById('just-titles').innerHTML =
            "<h2>" + outputString.substring(0, outputString.length - 1) + "</h2>";
    },
    showAjax: function (button) {
        event.preventDefault();
        const xhr = new XMLHttpRequest();
        let index = button.data('lp');
        let params = "number=" + document.getElementById('number' + index).value;
        let url = $('#PATH_check_film').val() + '?' + params;
        xhr.open("GET", url, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.addEventListener("load", e => {
            if (xhr.status === 200) {
                let just_titles = document.getElementById('just-titles');
                just_titles.innerHTML = xhr.response;
                let approveButton = document.createElement('button', 'ZATWIERDŹ');
                approveButton.setAttribute('name', 'approve');
                approveButton.setAttribute('class', 'approve');
                approveButton.setAttribute('data-lp', index);
                approveButton.innerHTML = 'ZATWIERDŹ';
                just_titles.appendChild(approveButton);
            }
        });
        xhr.send();

    },

    init: function() {
        $(document).on('click', '#add_more_fields', function () {
            gtnumbers.addField();
        });
        $(document).on('click', '#remove_fields', function () {
            gtnumbers.removeField();
        });
        $(document).on('click', '.check', function () {
            gtnumbers.showAjax($(this));
        });
        $(document).on('click', '.approve', function () {
            gtnumbers.approveInput($(this));
        });
        $(document).on('click', '.generate', function () {
            gtnumbers.generateOutput();
        });
    }
};

gtnumbers.init()