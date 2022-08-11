import React from 'react';
import {Box} from '@bigcommerce/big-design';

interface Props {
  children: React.ReactNode;
}

const PageBody = ({children}: Props) => {

  return (
    <Box marginVertical="xxLarge" marginHorizontal="xxxLarge">
      {children}
    </Box>
  )
}

export default PageBody;
