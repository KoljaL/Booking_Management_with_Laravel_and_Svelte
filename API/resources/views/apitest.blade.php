<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Booking Manager Test</title>
    <link
      rel="shortcut icon"
      href="data:image/svg+xml,%3Csvg xmlns='http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cg fill='none' stroke='%23f9322c' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5'%3E%3Cpath d='m4.25 9.75l-2-.5s0-1.5.5-3s4-1.5 4-1.5m-.5 7l.5 2s1.5 0 3-.5s1.5-4 1.5-4m-7 .5l2 2s5-2 6.5-4.5s1.5-5.5 1.5-5.5s-3 0-5.5 1.5s-4.5 6.5-4.5 6.5z'%2F%3E%3Cpath fill='%23f9322c' d='m1.75 14.25l2-1l-1-1z'%2F%3E%3Ccircle cx='10.25' cy='5.75' r='.5' fill='currentColor'%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E"
      type="image/x-icon"
    />

    <style>
      * {
        box-sizing: border-box;
        scroll-behavior: smooth;
      }

      *::-webkit-scrollbar {
        display: none;
      }

      * {
        -ms-overflow-style: none;
        scrollbar-width: none;
      }

      :root {
        --colors-gray1: hsl(0, 0%, 8.5%);
        --colors-gray2: hsl(0, 0%, 11%);
        --colors-gray3: hsl(0, 0%, 13.6%);
        --colors-gray4: hsl(0, 0%, 15.8%);
        --colors-gray5: hsl(0, 0%, 17.9%);
        --colors-gray6: hsl(0, 0%, 20.5%);
        --colors-gray7: hsl(0, 0%, 24.3%);
        --colors-gray8: hsl(0, 0%, 31.2%);
        --colors-gray9: hsl(0, 0%, 43.9%);
        --colors-gray10: hsl(0, 0%, 49.4%);
        --colors-gray11: hsl(0, 0%, 62.8%);
        --colors-gray12: hsl(0, 0%, 93%);
        --colors-gray13: hsl(0, 0%, 97%);
      }

      @font-face {
        font-family: "Geist";
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url("/fonts/Geist/Geist-Light.woff2") format("woff2");
      }

      @font-face {
        font-family: "Geist Mono";
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url("/fonts/GeistMono/GeistMono-Light.woff2") format("woff2");
      }

      body {
        margin: 0;
        padding: 0;
        font-family: "Geist", sans-serif;
        background-color: var(--colors-gray1);
        background-color: black;
        color: var(--colors-gray11);
      }

      main {
        display: grid;
        grid-template-columns: 250px 1fr;
        grid-template-rows: 1fr;
        height: 100vh;
        /* width: 100vw; */
        max-width: 80rem;
        margin: 0 auto;
      }

      h2 {
        margin: 0;
        padding: 0;
        font-size: 1.5rem;
        font-weight: normal;
        color: var(--colors-gray11);
      }

      input,
      button {
        font-size: 15px;
        margin: 0.5rem 0;
        padding: 0.15rem 0.5rem;
        border-radius: 0.25rem;
        border: 1px solid var(--colors-gray8);
        background-color: var(--colors-gray5);
        color: var(--colors-gray11);
        transition: all 0.2s ease-in-out;
        font-family: "Geist", sans-serif;
      }

      button {
        cursor: pointer;
      }

      button:hover {
        border: 1px solid #4f5b93;
      }

      input:focus,
      button:focus {
        outline: none;
        border: 1px solid #4f5b93;
      }

      form {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0;
      }

      .left {
        background-color: var(--colors-gray3);
        padding: 1rem;
        overflow: scroll;
      }

      .left header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
      }

      .left header button {
        border: 1px solid transparent;
        background-color: transparent;
      }

      .left header button.active {
        border: 1px solid #4f5b93;
        background-color: var(--colors-gray5);
      }

      .left h3 {
        margin-top: 5px;
        margin-bottom: 0;
        color: #4f5b93;
      }

      .left section {
        /* display: none; */
        margin: 1rem 0;
        flex-direction: column;
        align-items: flex-start;
        overflow: scroll;
        /* height: calc(100vh - 10rem); */
      }

      details {
        width: 100%;
        margin: 0;
        padding: 0;
        background-color: var(--colors-gray4);
        border-radius: 0.5rem;
        border: 1px solid var(--colors-gray8);
        margin-bottom: 1rem;
      }

      details summary {
        width: 100%;
        margin: 0;
        padding: 0;
        cursor: pointer;
        font-size: 1.2rem;
        background-color: var(--colors-gray5);
        color: var(--colors-gray11);
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
      }

      details[open] {
        border: 1px solid var(--colors-gray8);
      }

      details[open] summary {
        border-radius: 0.5rem 0.5rem 0 0;
        border: 0px solid transparent;
      }

      label {
        display: flex;
        flex-direction: row;
        align-items: baseline;
        gap: 0.5rem;
      }

      label input {
        width: 100%;
        margin-top: 0.5rem;
      }

      .left article {
        max-width: 100%;
        margin-inline: 0.5rem;
        padding-block: 5px;
        border-bottom: 1px solid #4f5b9355;
      }

      .left article:last-child {
        border-bottom: none;
      }

      .right {
        /* width: 100%; */
        /* height: 100%; */
        overflow: hidden;
        background-color: var(--colors-gray2);
        /* background-color: antiquewhite; */
        padding: 1rem;
      }

      .right pre {
        height: calc(100vh - 5rem);
        overflow: scroll;
        color: var(--colors-gray10);
        font-family: "Geist Mono";
        font-size: 14px;
        line-height: 1.7;
      }

      .right #resultUrl {
        color: var(--colors-gray10);
        font-weight: normal;
        padding-left: 1rem;
      }

      .right .method {
        color: var(--colors-gray11);
        font-size: 0.9rem;
        margin-inline: 1rem;
      }

      .newFormFieldButton {
        color: var(--colors-gray10);
        font-weight: bold;
        padding: 0.05rem 0.25rem 0.125rem 0.25rem;
        margin: 0;
        line-height: 1;
        margin-left: 0.5rem;
        position: relative;
        top: -3px;
      }

      .newFormFieldOverlay {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        background-color: var(--colors-gray3);
        padding: 0.75rem 1rem;
        border-radius: 0.25rem;
        border: 1px solid var(--colors-gray8);
      }

      .newFormFieldOverlay form {
        display: block;
      }

      .newFormFieldOverlay input {
        width: 122px;
        display: block;
      }

      .newFormFieldOverlay button:first-of-type {
        margin-right: 1rem;
      }
    </style>
  </head>

  <body>
    <main>
      <div class="left">
        <h2>API Tester</h2>
        <header>
          <form id="adminLoginForm" enctype="multipart/form-data">
            <input type="hidden" name="email" value="admin@example.com" />
            <input type="hidden" name="password" value="password" />
            <button type="submit" data-section="staff" id="adminLoginButton" data-url="login">Admin</button>
          </form>
          <form id="staffLoginForm" enctype="multipart/form-data">
            <input type="hidden" name="email" value="staff@example.com" />
            <input type="hidden" name="password" value="password" />
            <button type="submit" data-section="staff" id="staffLoginButton" data-url="login">Staff</button>
          </form>
          <form id="memberLoginForm" enctype="multipart/form-data">
            <input type="hidden" name="email" value="member@example.com" />
            <input type="hidden" name="password" value="password" />
            <button type="submit" data-section="member" id="memberLoginButton" data-url="login">Member</button>
          </form>
        </header>
        <!--
        <section id="staffSection">
          <details open>
            <summary>Member</summary>
            <article>
              <button id="allMember" data-url="member?all">All</button>
              <button id="activeMember" data-url="member">Active</button>
              <button id="inactiveMember" data-url="member?inactive">Inactive</button>
            </article>
            <article>
              <h3>one Member</h3>
              <label>
                Id:
                <input type="text" name="oneMemberId" style="width: 35px" value="1" />
              </label>
              <button id="oneMember" data-url="member">Get</button>
            </article>

            <article>
              <h3>create Member<button class="newFormFieldButton">+</button></h3>
              <form id="createMember">
                <label>
                  Name:
                  <input type="text" name="name" value="New Member" />
                </label>
                <label>
                  Email:
                  <input type="text" name="email" value="test@example.com" />
                </label>
                <button type="submit" id="createMemberButton" data-url="member">Create</button>
              </form>
            </article>

            <article>
              <h3>update Member<button class="newFormFieldButton">+</button></h3>
              <form id="updateMember" enctype="multipart/form-data">
                <label>
                  Id:
                  <input type="text" name="updateMemberId" style="width: 35px" value="1" />
                </label>
                <label>
                  Name:
                  <input type="text" name="name" value="name" />
                </label>
                <label>
                  Email:
                  <input type="text" name="email" value="email" />
                </label>
                <button type="submit" id="updateMemberButton" data-url="member">Update</button>
              </form>
            </article>
            <article>
              <h3>delete Member</h3>
              <label>
                Id:
                <input type="text" name="deleteMemberId" style="width: 35px" value="1" />
              </label>
              <button id="deleteMember" data-url="member">Delete</button>
            </article>
          </details>

        <details>
            <summary>Booking</summary>
            <article>
              <button id="allBooking" data-url="booking?all">All</button>
              <button id="activeBooking" data-url="booking">Active</button>
              <button id="inactiveBooking" data-url="booking?inactive">Inactive</button>
            </article>
            <article>
              <h3>one Booking</h3>
              <label>
                Id:
                <input type="text" name="oneBookingId" style="width: 35px" value="1" />
              </label>
              <button id="oneBooking" data-url="booking">Get</button>
            </article>

            <article>
              <h3>create Booking<button class="newFormFieldButton">+</button></h3>
              <form id="createBooking">
                <label>
                  Member Id:
                  <input type="text" name="memberId" value="1" />
                </label>
                <label>
                  Start:
                  <input type="text" name="start" value="2021-01-01 12:00:00" />
                </label>
                <label>
                  End:
                  <input type="text" name="end" value="2021-01-01 13:00:00" />
                </label>
                <button type="submit" id="createBookingButton" data-url="booking">Create</button>
              </form>
            </article>

            <article>
              <h3>update Booking<button class="newFormFieldButton">+</button></h3>
              <form id="updateBooking" enctype="multipart/form-data">
                <label>
                  Id:
                  <input type="text" name="updateBookingId" style="width: 35px" value="1" />
                </label>
                <label>
                  Member Id:
                  <input type="text" name="memberId" value="1" />
                </label>
                <label>
                  Start:
                  <input type="text" name="start" value="2021-01-01 12:00:00" />
                </label>
                <label>
                  End:
                  <input type="text" name="end" value="2021-01-01 13:00:00" />
                </label>
                <button type="submit" id="updateBookingButton" data-url="booking">Update</button>
              </form>
            </article>
            <article>
              <h3>delete Booking</h3>
              <label>
                Id:
                <input type="text" name="deleteBookingId" style="width: 35px" value="1" />
              </label>
              <button id="deleteBooking" data-url="booking">Delete</button>
            </article>
          </details> 
        </section>

        <section id="memberSection"></section>
        -->
      </div>
      <div class="right">
        <h2>Result <span id="resultUrl"></span></h2>
        <pre id="result"></pre>
      </div>
    </main>

    <script>
      const URL = "http://127.0.0.1:8000/api/";
      let token = null;
      let responseData = null;

      // LOGIN
      const adminLoginButton = document.getElementById("adminLoginButton");
      const staffLoginButton = document.getElementById("staffLoginButton");
      const memberLoginButton = document.getElementById("memberLoginButton");
      // RESULT
      const result = document.getElementById("result");
      const resultUrl = document.getElementById("resultUrl");

      window.onload = () => {
        createDocument().then(() => {
          adminLoginButton.click();
          addNewFormFields();
        });
      };

      const apiArray = [
        {
          user: "staff",
          cat: [
            {
              name: "Member",
              requests: [
                {
                  method: "GET",
                  url: "member?all",
                  description: "All",
                  fields: [],
                },
                {
                  method: "GET",
                  url: "member",
                  description: "Active",
                  fields: [],
                },
                {
                  method: "GET",
                  url: "member?inactive",
                  description: "Inactive",
                  fields: [],
                },
                {
                  method: "GET",
                  url: "member/{id}",
                  description: "Get one member",
                  callback: function () {
                    updateIds("member");
                  },
                  fields: [
                    {
                      name: "id",
                    },
                  ],
                },
                {
                  method: "POST",
                  url: "member",
                  description: "Create a member",
                  newFields: true,
                  callback: function () {
                    updateIds("member");
                  },
                  fields: [
                    {
                      name: "name",
                    },
                    {
                      name: "email",
                    },
                  ],
                },
                {
                  method: "PATCH",
                  url: "member/{id}",
                  description: "Edit a member",
                  newFields: true,
                  fields: [
                    {
                      name: "id",
                    },
                    {
                      name: "name",
                    },
                    {
                      name: "email",
                    },
                  ],
                },
              ],
            },
          ],
        },
        {
          user: "member",
          cat: [
            {
              name: "Booking",
              requests: [],
            },
          ],
        },
      ];

      //
      // CREATE DOCUMENT from apiArray
      //
      async function createDocument() {
        for (let api of apiArray) {
          const leftSide = document.querySelector(".left");
          const section = document.createElement("section");
          if (!api.cat) continue;
          section.id = api.user + "Section";
          for (let cat of api.cat) {
            // console.log(cat);
            const details = document.createElement("details");
            const summary = document.createElement("summary");
            summary.innerHTML = cat.name;
            details.open = true;
            details.appendChild(summary);
            section.appendChild(details);

            for (let req of cat.requests) {
              const hasFields = req.fields.length > 0 ? true : false;

              //
              // has form fields
              if (hasFields) {
                const article = document.createElement("article");
                const h3 = document.createElement("h3");
                h3.innerHTML = req.description;
                if (req.newFields) {
                  const button = document.createElement("button");
                  button.classList.add("newFormFieldButton");
                  button.dataset.form = req.description.replace(/\s/g, "");
                  button.innerHTML = "+";
                  h3.appendChild(button);
                }
                article.appendChild(h3);
                const form = document.createElement("form");
                form.id = req.description.replace(/\s/g, "");
                article.appendChild(form);
                details.appendChild(article);
                for (let param of req.fields) {
                  const label = document.createElement("label");
                  // label first letter uppercase
                  const labelName = param.name.charAt(0).toUpperCase() + param.name.slice(1);
                  label.innerHTML = /*html*/ `${labelName}: <input type="text" name="${param.name}" value="" />`;
                  form.appendChild(label);
                }
                const button = document.createElement("button");
                button.type = "submit";
                button.innerHTML = req.method;
                button.dataset.url = req.url;
                button.dataset.method = req.method;
                button.addEventListener("click", (e) => {
                  e.preventDefault();
                  const url = e.target.dataset.url;
                  const method = e.target.dataset.method;
                  postData(method, url, form);

                  setTimeout(() => {
                    if (req.callback) {
                      req.callback();
                    }
                  }, 100);
                });
                form.appendChild(button);
              }
              //
              // no form fields
              else {
                const button = document.createElement("button");
                button.type = "submit";
                button.innerHTML = req.description;
                button.style.marginTop = "0.75rem";
                button.style.marginLeft = "0.5rem";
                button.dataset.url = req.url;
                button.dataset.method = req.method;
                button.addEventListener("click", (e) => {
                  e.preventDefault();
                  const url = e.target.dataset.url;
                  const method = e.target.dataset.method;
                  postData(method, url);
                });
                details.appendChild(button);
              }
            }
          }
          leftSide.appendChild(section);
        }
      }

      function updateIds(section) {
        console.log("updateIds", section);
        console.log(responseData);
        console.log(responseData[section]);
        const allIds = document.querySelectorAll("input[name=id]");
        for (let id of allIds) {
          id.value = responseData[section].id;
        }
      }
      //
      // POST function
      //
      async function postData(method, url, form = null) {
        // console.log("postData", method, url, form);
        // if form has only one input field with name id, replace {id} in url with the value of that field
        if (form) {
          const idField = form.querySelector("input[name=id]");
          if (idField) {
            url = url.replace("{id}", idField.value);
          }
          if (form.children.length === 2) {
            form = null;
          }
        }
        resultUrl.innerHTML = `<span class="method">${method}</span> api/${url}`;

        let formData = null;
        if (form) {
          formData = new FormData(form);
          // remove all Id fields
          for (let key of formData.keys()) {
            if (key.endsWith("Id")) formData.delete(key);
          }
        }
        // strange bug where PATCH method doesn't work with FormData
        if (method === "PATCH") {
          formData = new URLSearchParams(formData);
        }
        // console.log("formData", formData);
        fetch(URL + url, {
          method: method,
          headers: {
            Authorization: "Bearer " + token,
            Accept: "application/json",
          },
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            result.innerHTML = jsonFormatHighlight(data);
            responseData = data;
            if (data.token) token = data.token;
            // console.log(data);
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      }

      //
      // LOGIN
      //
      for (let button of [adminLoginButton, staffLoginButton, memberLoginButton]) {
        button.addEventListener("click", (e) => {
          e.preventDefault();
          token = null;
          result.innerHTML = "";
          resultUrl.innerHTML = "";
          const url = button.dataset.url;
          const form = e.target.parentElement;
          postData("POST", url, form);
          document.getElementById("staffSection").style.display = "none";
          document.getElementById("memberSection").style.display = "none";
          document.getElementById(button.dataset.section + "Section").style.display = "flex";
          adminLoginButton.classList.remove("active");
          staffLoginButton.classList.remove("active");
          memberLoginButton.classList.remove("active");
          button.classList.add("active");
        });
      }

      //
      // ADD NEW FORM FIELDS
      //
      function addNewFormFields() {
        const newFormFieldButtons = document.querySelectorAll(".newFormFieldButton");
        for (let button of newFormFieldButtons) {
          // console.log(button);
          button.addEventListener("click", (e) => {
            e.preventDefault();
            const x = e.clientX;
            const y = e.clientY;
            const formElement = e.target.parentElement.nextElementSibling.id;
            // console.log(formElement);
            // crteate a small overlay with two inputs
            const overlay = document.createElement("div");
            overlay.classList.add("newFormFieldOverlay");
            overlay.style.top = y + "px";
            overlay.style.left = x + "px";
            overlay.innerHTML = /*html*/ `
          <form>
            <input type="text" name="newFormField" value="new field" />
            <button class="addNewFormField" data-form=${formElement}>Add</button>
            <button class="addNewFormFieldClose" >Close</button>
            </form>`;
            document.body.appendChild(overlay);

            overlay.getElementsByClassName("addNewFormFieldClose")[0].addEventListener("click", (e) => {
              e.preventDefault();
              overlay.remove();
            });

            overlay.getElementsByClassName("addNewFormField")[0].addEventListener("click", (e) => {
              e.preventDefault();
              const formElement = document.getElementById(e.target.dataset.form);
              const name = e.target.parentElement.querySelector("input").value;
              const label = document.createElement("label");
              label.innerHTML = /*html*/ `${name}: <input type="text" name="${name}" value="" />`;
              formElement.insertBefore(label, formElement.querySelector("button"));
              overlay.remove();
            });
          });
        }
      }

      // MEMBER
      // const allMemberButton = document.getElementById("allMember");
      // const activeMemberButton = document.getElementById("activeMember");
      // const inactiveMemberButton = document.getElementById("inactiveMember");
      // const oneMemberButton = document.getElementById("oneMember");
      // const oneMemberId = document.querySelector("input[name=oneMemberId]");
      // const createMemberButton = document.getElementById("createMemberButton");
      // const createMemberForm = document.getElementById("createMember");
      // const updateMemberId = document.querySelector("input[name=updateMemberId]");
      // const updateMemberButton = document.getElementById("updateMemberButton");
      // const updateMemberForm = document.getElementById("updateMember");
      // const deleteMemberId = document.querySelector("input[name=deleteMemberId]");
      // const deleteMemberButton = document.getElementById("deleteMember");
      // BOOKINGS
      // const allBookingButton = document.getElementById("allBooking");
      // const activeBookingButton = document.getElementById("activeBooking");
      // const inactiveBookingButton = document.getElementById("inactiveBooking");
      // const oneBookingButton = document.getElementById("oneBooking");
      // const oneBookingId = document.querySelector("input[name=oneBookingId]");
      // const createBookingButton = document.getElementById("createBookingButton");
      // const createBookingForm = document.getElementById("createBooking");
      // const updateBookingId = document.querySelector("input[name=updateBookingId]");
      // const updateBookingButton = document.getElementById("updateBookingButton");
      // const updateBookingForm = document.getElementById("updateBooking");
      // const deleteBookingId = document.querySelector("input[name=deleteBookingId]");
      // const deleteBookingButton = document.getElementById("deleteBooking");

      // //
      // // DELETE MEMBER
      // //
      // deleteMemberButton.addEventListener("click", (e) => {
      //   e.preventDefault();
      //   const url = deleteMemberButton.dataset.url + "/" + deleteMemberId.value;
      //   postData("DELETE", url);
      // });

      // //
      // // CREATE MEMBER
      // //
      // createMemberForm.addEventListener("submit", (e) => {
      //   e.preventDefault();
      //   const url = createMemberButton.dataset.url;
      //   postData("POST", url, createMemberForm);
      // });

      // //
      // // GET ONE MEMBER
      // //
      // oneMemberButton.addEventListener("click", (e) => {
      //   e.preventDefault();
      //   const url = oneMemberButton.dataset.url + "/" + oneMemberId.value;
      //   postData("GET", url);
      // });

      // updateMemberForm.addEventListener("submit", (e) => {
      //   e.preventDefault();
      //   const url = updateMemberButton.dataset.url + "/" + updateMemberId.value;
      //   postData("PATCH", url, updateMemberForm);
      // });

      // //
      // // GET ALL MEMBERS
      // //
      // for (let button of [allMemberButton, activeMemberButton, inactiveMemberButton]) {
      //   button.addEventListener("click", (e) => {
      //     e.preventDefault();
      //     const url = button.dataset.url;
      //     postData("GET", url);
      //   });
      // }

      // //
      // // GET ALL BOOKINGS
      // //
      // for (let button of [allBookingButton, activeBookingButton, inactiveBookingButton]) {
      //   button.addEventListener("click", (e) => {
      //     e.preventDefault();
      //     const url = button.dataset.url;
      //     postData("GET", url);
      //   });
      // }

      // //
      // // GET ONE BOOKING
      // //
      // oneBookingButton.addEventListener("click", (e) => {
      //   e.preventDefault();
      //   const url = oneBookingButton.dataset.url + "/" + oneBookingId.value;
      //   postData("GET", url);
      // });

      // //
      // // CREATE BOOKING
      // //
      // createBookingForm.addEventListener("submit", (e) => {
      //   e.preventDefault();
      //   const url = createBookingButton.dataset.url;
      //   postData("POST", url, createBookingForm);
      // });

      // //
      // // UPDATE BOOKING
      // //
      // updateBookingForm.addEventListener("submit", (e) => {
      //   e.preventDefault();
      //   const url = updateBookingButton.dataset.url + "/" + updateBookingId.value;
      //   postData("PATCH", url, updateBookingForm);
      // });

      // //
      // // DELETE BOOKING
      // //
      // deleteBookingButton.addEventListener("click", (e) => {
      //   e.preventDefault();
      //   const url = deleteBookingButton.dataset.url + "/" + deleteBookingId.value;
      //   postData("DELETE", url);
      // });

      //
      // ENTER key submits form
      //
      document.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
          e.preventDefault();
          const button = e.target.parentElement.nextElementSibling;
          button.click();
        }
      });

      const defaultColors = {
        keyColor: "rgb(239, 89, 111)",
        numberColor: "hsl(39.1, 67.1%, 69%)",
        stringColor: "hsl(107.6, 43.6%, 63.1%)",
        trueColor: "rgb(97, 175, 239)",
        falseColor: "rgb(213, 95, 222)",
        nullColor: "hsl(39.1, 67.1%, 69%)",
      };

      const entityMap = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;",
        "`": "&#x60;",
        "=": "&#x3D;",
      };

      function escapeHtml(html) {
        return String(html).replace(/[&<>"'`=]/g, function (s) {
          return entityMap[s];
        });
      }

      function jsonFormatHighlight(json, colorOptions = {}) {
        const valueType = typeof json;
        if (valueType !== "string") {
          json = JSON.stringify(json, null, 2) || valueType;
        }
        let colors = Object.assign({}, defaultColors, colorOptions);
        json = json.replace(/&/g, "&").replace(/</g, "<").replace(/>/g, ">");
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+]?\d+)?)/g, (match) => {
          let color = colors.numberColor;
          let style = "";
          if (/^"/.test(match)) {
            if (/:$/.test(match)) {
              color = colors.keyColor;
            } else {
              color = colors.stringColor;
              match = '"' + escapeHtml(match.substr(1, match.length - 2)) + '"';
              style = "word-wrap:break-word;white-space:pre-wrap;";
            }
          } else {
            color = /true/.test(match) ? colors.trueColor : /false/.test(match) ? colors.falseColor : /null/.test(match) ? colors.nullColor : color;
          }
          match = match.replace(/"/g, "");
          return `<span style="${style}color:${color}">${match}</span>`;
        });
      }
    </script>
  </body>
</html>
