jQuery(document).ready(function ($) {



    $(".search_section_form").change(function () {
        get_post_data();
    });


    function get_post_data() {
       // e.preventDefault();
        let data = {
            action: 'search_form_function',
            category: $('#category').val()
        }

        $.ajax({
            url: ajax_url,
            data: data,
            beforeSend: function () {
                $('#form_loader_img').show();
                $('.search_section .empty').hide();
                $('.search_section_content').hide();
                //  console.log('beforeSend')
            },
            success: (response) => {
               // console.log('success')
                $('.search_section_content').show();
                $('#form_loader_img').hide();
                $('.search_section .empty').show();


                const pagList = document.querySelectorAll('.pagination');
                const showList = document.querySelectorAll('.search_section_content');


                function paginationBtn(arr, size = 3) {
                    let btn = '';

                    pagList.forEach((elem, i) => {
                        for (let i = 0; i < response.length / size; i++) {
                            btn += `<button class='pagination_btn'>${i + 1}</button>`
                        }
                        elem.innerHTML = btn;
                    });
                }

                paginationBtn(response);
                const btnPag = document.querySelectorAll('.pagination_btn');

                function postList(page, size = 3) {

                    let arrayList = [];
                    arrayList = response.slice().splice(page * size, size);

                    showList.forEach((elem,i) => {
                        let item = '';
                        for(let i = 0; i < arrayList.length; i++){
                            if (arrayList[i].featuredImgUrl == false) {
                                src_img = 'https://placehold.jp/300x200.png';

                            } else {
                                src_img = arrayList[i].featuredImgUrl;
                            }
                            let categories_data = arrayList[i].category
                            let categories_title = [];

                            categories_data.forEach( (item, i) => {
                                categories_title.push(item.name);
                            })
                            let categories_title_str = categories_title.join(" | ")

                           // console.log(arrayList[i].postMeta.description)



                            item += `<div class="post_wrapper">
                             <a href=` + arrayList[i].permalink + ` class="link">
                                 <div class="block">
                                 <img src=` + src_img + ` alt=` + arrayList[i].alt + `>
                    
                                     <div class="data_wr">
                                         <h2 class="subtitle">` + arrayList[i].title + `</h2>
                                         <h3>Category: ` + categories_title_str + ` </h3>
                                         <p>` + arrayList[i].theExcerpt + `</p>
                                         ${ arrayList[i].postMeta.title ?  "<p>Meta title: " + arrayList[i].postMeta.title + "</p>" : '' }
                                         ${ arrayList[i].postMeta.description ?  "<p>Meta desc: " + arrayList[i].postMeta.description + "</p>" : '' }
                                         
                                     </div>
                                 </div>
                             </a>
                         </div>`
                        }
                        elem.innerHTML = item;
                    })

                    if(btnPag.length > 0) {
                        btnPag[0].classList.add('pagination_btn-active');
                    }

                }

                function addClass(btnElem, prevBtn){
                    prevBtn.forEach(elem => elem.classList.remove('pagination_btn-active'));
                    btnElem.classList.add('pagination_btn-active');
                }

                btnPag.forEach((elem,i) => {
                    elem.addEventListener('click', () => {postList(i); addClass(elem,btnPag);});
                });

                postList(0);

                if (response.length === 0) {
                    content = `<div class="empty">Ничего не найдено</div>`
                }
            }
        })
    }


    $(document).ready( get_post_data() )

});



