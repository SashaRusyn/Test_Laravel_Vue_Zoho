<template>
    <div>
        <nav>
            <a @click="activeForm = 'form1'">Account Form</a>
            <a @click="activeForm = 'form2'">Deal Form</a>
        </nav>

        <div v-if="activeForm === 'form1'">
            <h3>Account Form</h3>
            <p>{{ validateError.website ? validateError.website : '' }}</p>
            <p>{{ validateError.phone ? validateError.phone : '' }}</p>
            <form @submit.prevent="submitForm1">
                <label>
                    Account name:
                    <input type="text" v-model="form1Data.name" minlength="3" required/>
                </label>
                <label>
                    Account website:
                    <input type="text" v-model="form1Data.website" @change="validateWebsite()" required/>
                </label>
                <label>
                    Account phone:
                    <input type="text" v-model="form1Data.phone" @change="validatePhone()" required/>
                </label>
                <button type="submit">Create</button>
            </form>
        </div>

        <div v-if="activeForm === 'form2'">
            <h3>Deal Form</h3>
            <form @submit.prevent="submitForm2">
                <label>
                    Deal name:
                    <input type="text" v-model="form2Data.deal_name" minlength="3" required>
                </label>
                <label>
                    Account name:
                    <select v-model="form2Data.account_name" id="account" required>
                        <option v-for="account in accounts" :key="account.id" :value="account.Account_Name">
                            {{ account.Account_Name }}
                        </option>
                    </select>
                </label>
                <label>
                    Closing date:
                    <input type="date" v-model="form2Data.closing_date" required/>
                </label>
                <label>
                    Deal stage:
                    <select v-model="form2Data.stage" required>
                        <option value="Qualification">Qualification</option>
                        <option value="Needs Analysis">Needs Analysis</option>
                        <option value="Value Proposition">Value Proposition</option>
                        <option value="Identify Decision Makers">Identify Decision Makers</option>
                        <option value="Proposal/Price Quote">Proposal/Price Quote</option>
                        <option value="Negotiation/Review">Negotiation/Review</option>
                        <option value="Closed/Won">Closed/Won</option>
                        <option value="Closed/Lost">Closed/Lost</option>
                        <option value="Closed/Lost to Competition">Closed/Lost to Competition</option>
                    </select>
                </label>
                <button type="submit">Create</button>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    data() {
        return {
            activeForm: 'form1',
            form1Data: {
            },
            form2Data: {
            },
            accounts: [],
            error: null,
            validateError: {}
        };
    },

    methods: {
        async submitForm1() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('http://localhost:8000/api/accounts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    accountName: this.form1Data.name,
                    accountWebsite: this.form1Data.website,
                    accountPhone: this.form1Data.phone
                })});
                const result = await response.json();
                if (result.success) {
                    alert('Successful creating account')
                } else {
                    alert('Error with creating account');
                }
            } catch (error) {
                alert('Error with creating account');
            }
        },

        async submitForm2() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('http://localhost:8000/api/deals', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        name: this.form2Data.deal_name,
                        stage: this.form2Data.stage,
                        closingDate: this.form2Data.closing_date,
                        accountName: this.form2Data.account_name
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert('Successful creating deal');
                } else {
                    alert('Error with creating deal');
                }
            } catch (error) {
                alert('Error with creating deal aaa');
            }
        },

        async fetchAccounts() {
            try {
                const response = await axios.get('http://localhost:8000/api/accounts');
                this.accounts = response.data.data;
            } catch (error) {
                this.error = 'Failed to retrieve accounts: ' + error.message;
            }
        },

        validateWebsite() {
            const urlRegex = /^[\w.-]+\.[a-zA-Z]{2,6}$/;
            if (!urlRegex.test(this.form1Data.website)) {
                this.validateError.website = "Invalid website format\n";
            } else {
                this.validateError.website = "";
            }
        },

        validatePhone() {
            const urlRegex = /^(\+\d{1,3}[- ]?)?\d{10}$/;
            if (!urlRegex.test(this.form1Data.phone)) {
                this.validateError.phone = "Invalid phone format\n";
            }  else {
                this.validateError.phone = "";
            }
        },
    },

    mounted() {
        this.fetchAccounts();
    },
};
</script>

<style>
* {
    margin: 0;
    padding: 0;
    background: none;
    outline: none;
    border: none;
}

nav {
    width: 100%;
    display: flex;
    flex-direction: row;
    background-color: cadetblue;
    min-height: 30px;
    gap: 30px;
    padding-left: 50px;
    font-weight: 700;
    cursor: pointer;
}

nav a {
    color: white;
    padding: 5px;
}

form {
    display: flex;
    flex-direction: column;
    margin: 0 auto;
    width: 90%;
    max-width: 600px;
    gap: 25px;
}

form>* {
    width: 100%;
}

form label {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

h3, p {
    text-align: center;
    padding: 10px;
}

input,
select {
    border: 1px solid gray;
    border-radius: 5px;
    padding: .5rem;
}

button {
    border: 1px solid gray;
    border-radius: 5px;
    padding: 5px;
    cursor: pointer;
}

button:hover {
    background-color: gray;
    color: white;
}
</style>