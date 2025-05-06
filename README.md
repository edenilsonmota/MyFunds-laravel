
# 📄 Documentação de Testes com Postman – MyFunds API

## 🔐 Autenticação

Use Laravel Sanctum:

### Register
**POST** `api/register`  
**Body (JSON):**
```json
{
  "name": "Edenilson",
  "email": "eden@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```
**Resposta:**
```json
{
  "message": "Usuário registrado com sucesso.",
  "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
  "user": {
    "name": "Edenilson",
    "email": "eden@example.com",
    "created_at": "2025-05-06T02:01:37.000000Z",
    "id": 5
  }
}

```


### Login
**POST** `/api/login`  
**Body (JSON):**
```json
{
  "email": "user@example.com",
  "password": "password"
}
```
**Resposta:**
```json
{
  "token": "seu_token_aqui"
}
```
**OBS:** Use o token retornado em todas as próximas requisições como:
```
Authorization: Bearer seu_token_aqui
```

---

## 💰 1. Depósito

**POST** `/api/deposit`  
**Headers:**
```
Authorization: Bearer seu_token
Accept: application/json
Content-Type: application/json
```
**Body (JSON):**
```json
{
  "amount": 150.00
}
```
**Resposta:**
```json
{
  "message": "Depósito realizado com sucesso.",
  "transaction": {
    "id": 1,
    "receiver_id": 2,
    "type": "deposit",
    "amount": "150.00",
    "status": "completed"
  }
}
```

---

## 🔁 2. Transferência entre usuários

**POST** `/api/transfer`  
**Body (JSON):**
```json
{
  "receiver_id": 3,
  "amount": 50.00,
  "description": "Pagamento mensal"
}
```
**Resposta:**
```json
{
  "message": "Transferência realizada com sucesso.",
  "transaction": {
    "id": 2,
    "sender_id": 2,
    "receiver_id": 3,
    "type": "transfer",
    "amount": "50.00",
    "status": "completed"
  }
}
```

---

## ↩️ 3. Reversão de Transação

**POST** `/api/reverse/2`  
(onde `2` é o ID da transação a ser revertida)  
**Body (JSON):**
```json
{
  "reason": "Transação feita por engano"
}
```
**Resposta:**
```json
{
  "message": "Transação revertida com sucesso.",
  "reversal": {
    "id": 1,
    "original_transaction_id": 2,
    "reversed_by": 2,
    "reason": "Transação feita por engano"
  }
}
```

---

## ↩️ 4. Todas transferencias

**GET** `/api/transactions`  

**Resposta:**
```json
{
  "message": "Transação revertida com sucesso.",
  "reversal": {
    "id": 1,
    "original_transaction_id": 2,
    "reversed_by": 2,
    "reason": "Transação feita por engano"
  }
}
```

---

## ❌ Exemplo de erro (saldo insuficiente)

```json
{
  "message": "Saldo insuficiente."
}
```
