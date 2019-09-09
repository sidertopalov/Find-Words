"use strict";

/**
* @TODO Extract bg words
* from https://slovored.com/words.html ( https://slovored.com/search/scrollwords?isUTF8=true&dictionary=unilingual&word=аба&direction=next )
*/

Array.prototype.containsArray = function(search)
{
    var searchJson = JSON.stringify(search); // "[3,566,23,79]"
    var arrJson = this.map(JSON.stringify); // ["[2,6,89,45]", "[3,566,23,79]", "[434,677,9,23]"]

    return arrJson.indexOf(searchJson);
};

/**
* View functions
*/
var selectorCell;
var drawMatrix = function(data, container) {
    data.forEach(function(val, row) {
        val.forEach(function(symbol, col) {
            selectorCell = 'div#cell_' + row + '' + col;
            container.find(selectorCell).text(symbol);
        });
    });

    return container;
};

var drawMainMatrix = function(grid) {
    var container = $('div#matrix');
    var selectorCell;

    drawMatrix(grid, container);
};

/**
* Result render
*/
var printResult = function(grid, positions) {
    
    var data = Object.values(positions);
    $('div#totalWord').text('Total Words: ' + data.length);

    var container = $('div#container-grid-result');
    container.html('');
    var template = $('div#grid_copy');
    
    var steps;
    var selectorCell;

    /**
    * appending new html to container and return it
    */
    var gridFilled = false;
    var appendNewItem = function(grid, id) {
        if(gridFilled === false) {
            template = drawMatrix(grid, template);
            template.hide();
        }

        gridFilled = template.clone();
        gridFilled.attr('id', id);

        container.append(gridFilled);
        gridFilled.show();
        return gridFilled;
    };
    
    data.forEach(function(pos, k) {
        var containerId = 'grid_' + k;
        var ele = appendNewItem(grid, containerId);
        
        // attach click event to button to remove the answer
        ele.find('button#closeBtn').on('click', function(e) {
            $(this).parent().remove();
        });
        
        var info = ele.find('div.info');
        steps = Object.values(pos.visitedPositions);
        
        steps.forEach(function(value, key) {
            if(!$.isFunction(value)) {
                if(key == 0) {
                    info.find('div#fullWord').append('#' + (k+1) + ' ' + pos.value);
                }
                if(key > 0) {
                    info.find('div#letters').append(' &rarr; ');
                    info.find('div#cell').append(' &rarr; ');
                }
                selectorCell = 'div#cell_' + value[0] + '' + value[1];

                if(key == 0) {
                    ele.find(selectorCell).addClass('bg-danger');
                } else {
                    ele.find(selectorCell).addClass('bg-success');
                }
                info.find('div#letters').append(' <b>' + pos.value[key] + '</b> ');
                info.find('div#cell').append( '( ' + value[0] + ',' + value[1] + ' ) ' );
            }
        });
    });
};

/**
* END View functions
*/

var convertStringToGrid = function(arr, startIndex, chunkLen) {
    var chunk = [];
    
    var temp;
    while (arr.length > 0) {
        temp = arr.splice(startIndex, chunkLen)
        chunk.push(temp);
    }
    return chunk;
};

var spinningButtonStart = function(btnElem) {
    $(btnElem).prop("disabled", true);
    // add spinner to button
    $(btnElem).html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
    );
};

var spinningButtonStop = function(btnElem, html) {
    $(btnElem).prop("disabled", false);
    $(btnElem).html(
        html
    );
};


/**
* Main code
*/

var boardSearch = new BoardSearch();

// Loading dictionary data in Trie Data Structure for faster search
var trie = new Trie();
$.each(bg_dictionary, function(word) {
    trie.add(word.toLowerCase());
});

var solveMatrix = function(btnElem) {
    var submitBtnText = $(btnElem).text();
    spinningButtonStart(btnElem);
    setTimeout(() => {
        var str = $('input#enterMatrix').val().toLowerCase();
        if(str.length) {
            
            var arr = str.split('');
            var gridMatrix = convertStringToGrid(arr, 0, 4);
            
            drawMainMatrix(gridMatrix);
            
            boardSearch.init(gridMatrix, trie);
            boardSearch.search(function(result) {
                spinningButtonStop(btnElem, submitBtnText);
                printResult(gridMatrix, result);
            });
        }
    }, 100);
};

$( document ).ready(function() {
    $('input#enterMatrix').val(dummy_matrix.toString().replace(/,/gi, ''));

    // pm form submit prevent
    // this is just to allow enter for submit
    $("form#solveForm").on("submit", function (e) {
        e.preventDefault(this);
    });
});