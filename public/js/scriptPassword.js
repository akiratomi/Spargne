let fruits = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

fruitsShuffle = shuffle(fruits);

for (let i = 0; i < fruits.length; i++) {
    $('#numbersPassword').append('<div id=' + fruits[i] + ' class="numPassWord col-2 mr-1 mb-2"><p>' + fruits[i] + '</p></div>');
}

$('#numbersPassword').append('<div id="del" class="numPassWord col-2 mr-1 mb-2"><p>D</p></div>');

function shuffle(array) {
    let currentIndex = array.length,
        randomIndex;

    // While there remain elements to shuffle...
    while (currentIndex != 0) {

        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex--;

        // And swap it with the current element.
        [array[currentIndex], array[randomIndex]] = [
            array[randomIndex], array[currentIndex]
        ];
    }

    return array;
}

for (let i = 0; i < fruits.length; i++) {
    $('#' + i).click(function() {
        if ($('#inputPassword').val().length < 8) {
            $('#inputPassword').val($('#inputPassword').val() + i)
        }
    })
}

$('#del').click(function() {
    $('#inputPassword').val("")
})

/* big bug */