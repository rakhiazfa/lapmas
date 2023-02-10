/**
 * Auth actions.
 *
 */

import { createAsyncThunk } from "@reduxjs/toolkit";
import axios from "axios";
import axiosWithToken from "../../services/axiosWithToken";

export const login = createAsyncThunk(
    "auth/login",
    async (payload, { rejectWithValue }) => {
        try {
            const { data } = await axios.post("/auth/login", payload);

            return data;
        } catch (error) {
            return rejectWithValue(error.response.data);
        }
    }
);

export const getUser = createAsyncThunk(
    "auth/user",
    async (payload, { rejectWithValue }) => {
        try {
            const { data } = await axiosWithToken.get("/user");

            return data;
        } catch (error) {
            return rejectWithValue(error.response.data);
        }
    }
);
