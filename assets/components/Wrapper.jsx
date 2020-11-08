import React, { Component } from 'react'
import { Layout } from 'antd'
import HeaderContent from './HeaderContent'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import Home from './Home'
import TrickForm from './TrickForm'

const { Header, Content } = Layout

export default function Wrapper ({ children }) {
  return (

    <Layout>
    <BrowserRouter>
      <Header><HeaderContent/></Header>
      <Content>
        <Switch>
          <Route exact path='/'>
            <Home/>
          </Route>
          <Route exact path='/test'>
            <TrickForm/>
          </Route>
        </Switch>
      </Content>
    </BrowserRouter>
    </Layout>
  )
}