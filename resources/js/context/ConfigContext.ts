import {createContext} from 'react';
import {ContextType} from "../types";

const ConfigContext = createContext<ContextType|undefined>(undefined);
export default ConfigContext;
